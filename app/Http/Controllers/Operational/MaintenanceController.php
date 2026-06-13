<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceReport;
use App\Models\MaintenancePhoto;
use App\Models\MaintenanceFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MaintenanceReport::with(['creator', 'photos', 'feedback'])
            ->orderBy('created_at', 'desc');

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // STATUS FILTER
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $maintenances = $query->paginate(10);

        return view('operational.maintenance.index', compact('maintenances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $maintenance = MaintenanceReport::create([
                'title' => $request->title,
                'description' => $request->description,
                'created_by' => auth()->id(),
                'status' => 'open',
            ]);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('maintenance', 'public');

                    MaintenancePhoto::create([
                        'maintenance_report_id' => $maintenance->id,
                        'file_path' => $path,
                    ]);
                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Maintenance berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maintenance = MaintenanceReport::with(['creator', 'photos', 'feedback', 'feedback.creator'])
            ->findOrFail($id);

        return view('operational.maintenance.show', compact('maintenance'));
    }

    /**
     * Update description - Staff only
     */
    public function updateDescription(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $maintenance = MaintenanceReport::findOrFail($id);

        if (auth()->user()->role !== 'staf') {
            abort(403, 'Hanya staff yang dapat memperbarui deskripsi');
        }

        if ($maintenance->status === 'resolved') {
            return back()->with('error', 'Tidak dapat memperbarui maintenance yang sudah diselesaikan');
        }

        $maintenance->update([
            'description' => $request->description,
        ]);

        return back()->with('success', 'Deskripsi berhasil diperbarui');
    }

    /**
     * Add feedback - Owner only
     */
    public function addFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $maintenance = MaintenanceReport::with('feedback')->findOrFail($id);

        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya owner yang dapat mengirim feedback');
        }

        if ($maintenance->status === 'resolved') {
            return back()->with('error', 'Tidak dapat mengirim feedback ke maintenance yang sudah diselesaikan');
        }

        if ($maintenance->feedback) {
            return back()->with('error', 'Feedback sudah ada sebelumnya');
        }

        MaintenanceFeedback::create([
            'maintenance_report_id' => $id,
            'feedback' => $request->feedback,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Feedback berhasil dikirim');
    }

    /**
     * Resolve - Staff only
     */
    public function resolve($id)
    {
        $maintenance = MaintenanceReport::findOrFail($id);

        if (auth()->user()->role !== 'staf') {
            abort(403, 'Hanya staff yang dapat menyelesaikan maintenance');
        }

        $maintenance->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Maintenance berhasil diselesaikan');
    }
}