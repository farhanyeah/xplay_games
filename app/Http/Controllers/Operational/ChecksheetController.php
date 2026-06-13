<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\ChecksheetDetail;
use App\Models\ChecksheetHeader;
use App\Models\ChecksheetItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecksheetController extends Controller
{
    
    private function detectShift()
    {
        $hour = (int) Carbon::now()->format('H');

        // Jam 10:00 - 17:59 = Shift Pagi
        if ($hour >= 10 && $hour < 18) {
            return 'pagi';
        }

        // Jam 18:00 - 02:59 = Shift Malam
        return 'malam';
    }

    private function generateChecksum($date = null)
    {
        $date = $date ?? Carbon::now();
        $dateStr = $date->format('Ymd');
        
        $lastHeader = ChecksheetHeader::whereDate('date', $date->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = 1;
        if ($lastHeader) {
            $lastSeq = (int) substr($lastHeader->checksum, -3);
            $sequence = $lastSeq + 1;
        }
        
        return 'CS-' . $dateStr . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
    public function index()
    {
        $today = Carbon::now()->toDateString();
        $currentShift = $this->detectShift();

        // CEK: Apakah sudah ada checksheet untuk shift ini? (ABAIKAN TANGGAL)
        $existingHeader = ChecksheetHeader::where('shift', $currentShift)
            ->whereDate('date', $today)
            ->first();

        // Load existing details
        $existingDetails = [];
        if ($existingHeader) {
            foreach ($existingHeader->details as $detail) {
                $existingDetails[$detail->checksheet_item_id] = $detail->status;
            }
        }

        // Daily items based on shift
        $dailyItems = ChecksheetItem::where('is_active', true)
            ->where('frequency', 'daily')
            ->where(function ($query) use ($currentShift) {
                $query->where('shift', $currentShift)
                    ->orWhere('shift', 'semua');
            })
            ->orderBy('shift')
            ->orderBy('name')
            ->get();
        
        // Biweekly items
        $biweeklyItems = ChecksheetItem::where('is_active', true)
            ->where('frequency', 'biweekly')
            ->orderBy('name')
            ->get();
        
        // Monthly items
        $monthlyItems = ChecksheetItem::where('is_active', true)
            ->where('frequency', 'monthly')
            ->orderBy('name')
            ->get();

        // History for owner
        $riwayatChecksheets = ChecksheetHeader::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('operational.checksheet.index', compact(
            'dailyItems',
            'biweeklyItems',
            'monthlyItems',
            'existingHeader',
            'existingDetails',
            'currentShift',
            'riwayatChecksheets'
        ));
    }

    public function store(Request $request)
    {
        $currentShift = $this->detectShift();
        $today = Carbon::now()->toDateString();

        // CEK: Apakah sudah ada header untuk shift ini?
        $header = ChecksheetHeader::where('shift', $currentShift)
            ->whereDate('date', $today)
            ->first();

        // Jika ada dan user BERBEDA, HITUNG berapa sudah terisi
        if ($header && $header->user_id != Auth::id()) {
            $totalItems = ChecksheetItem::where('is_active', true)
                ->where('frequency', 'daily')
                ->where(function ($query) use ($currentShift) {
                    $query->where('shift', $currentShift)
                        ->orWhere('shift', 'semua');
                })
                ->count();
            
            $checkedCount = ChecksheetDetail::where('checksheet_header_id', $header->id)
                ->where('status', 'done')
                ->count();
            
            // Kalau udah FULL, TIDAK BOLEH
            if ($checkedCount >= $totalItems) {
                return back()->with('error', 'Checksheet shift ' . ucfirst($currentShift) . ' sudah diisi pada tanggal ' . Carbon::parse($today)->format('d M Y'));
            }
        }

        // Validasi: minimal 1 item harus dicentang
        $checkedItems = array_filter($request->items ?? [], function($v) {
            return $v === 'done';
        });
        
        if (empty($checkedItems)) {
            return back()->with('error', 'Pilih minimal 1 item untuk disimpan!')
                ->withInput();
        }
        
        // Buat baru jika belum ada
        if (!$header) {
            $header = ChecksheetHeader::create([
                'checksum' => $this->generateChecksum(Carbon::now()),
                'date' => $today,
                'shift' => $currentShift,
                'user_id' => Auth::id(),
                'status' => 'completed',
            ]);
        }

        // Save / Update details
        foreach ($request->items as $itemId => $status) {
            ChecksheetDetail::updateOrCreate(
                [
                    'checksheet_header_id' => $header->id,
                    'checksheet_item_id' => $itemId,
                ],
                [
                    'status' => $status,
                ]
            );
        }
        
        return redirect()
            ->route('operational.checksheet')
            ->with('success', 'Checksheet berhasil disimpan!');
    }
    public function manage(Request $request)
    {
        $query = ChecksheetItem::query();
        
        // Filter search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter frekuensi
        if ($request->frekuensi) {
            $query->where('frequency', $request->frekuensi);
        }
        
        // Filter shift
        if ($request->shift) {
            $query->where('shift', $request->shift);
        }
        
        // Filter status (is_active)
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }
        
        $items = $query->orderBy('frequency',)
            ->orderBy('shift')
            ->orderBy('name')
            ->get();
        
        return view('operational.checksheet.manage', compact('items'));
    }

    public function itemStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'frequency' => 'required|in:daily,biweekly,monthly',
            'shift' => 'required|in:pagi,malam,semua',
        ]);
        
        ChecksheetItem::create($request->all());
        
        return redirect()
            ->route('operational.checksheet.manage')
            ->with('success', 'Item checksheet berhasil ditambahkan!');
    }

    public function itemUpdate(Request $request, ChecksheetItem $checksheet_item)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'frequency' => 'required|in:daily,biweekly,monthly',
        'shift' => 'required|in:pagi,malam,semua',
    ]);
    
    // TAMBAHKAN INI - Biar aktif/nonaktif works!
    $data = $request->all();
    $data['is_active'] = $request->has('is_active') ? true : false;
    
    $checksheet_item->update($data);
    
    return redirect()
        ->route('operational.checksheet.manage')
        ->with('success', 'Item checksheet berhasil diperbarui!');
}

    public function itemDelete(ChecksheetItem $checksheet_item)
    {
        $checksheet_item->delete();
        
        return redirect()
            ->route('operational.checksheet.manage')
            ->with('success', 'Item checksheet berhasil dihapus!');
    }

    public function ownerIndex(Request $request)
    {
        $query = ChecksheetHeader::with('user')
        ->orderBy('date', 'desc')
        ->orderBy('shift', 'desc');

        // Filter tanggal
        if ($request->tanggal) {
            $query->whereDate('date', $request->tanggal);
        }
    
        // Filter shift
        if ($request->shift) {
            $query->where('shift', $request->shift);
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }   
    
        $riwayatChecksheets = $query->limit(5)->get();
        return view('operational.checksheet.riwayat', compact('riwayatChecksheets'));
    }

    public function show(ChecksheetHeader $checksheetHeader)
    {
        // Load details dengan item
        $checksheetHeader->load('details.item');
    
        return view('operational.checksheet.show', compact('checksheetHeader'));
    }
}