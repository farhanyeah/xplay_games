<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\Sewa;
use App\Models\UnitSewa;
use Illuminate\Http\Request;


class SewaOperasionalController extends Controller
{
        public function index(Request $request)
    {
        // Default: hari ini kalau ga ada tanggal
        if (!$request->filled('tanggal')) {
            $request->merge(['tanggal' => now()->toDateString()]);
        }

        $query = Sewa::with(['unitSewa.jenisUnit', 'user', 'paket'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status_sewa', $request->status);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_mulai', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_code', 'like', '%' . $request->search . '%')
                ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }

        $sewas = $query->paginate(10)->withQueryString();
        $units = UnitSewa::with('jenisUnit')->get();

        $pendapatanHariIni = Sewa::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_harga');

        return view('operational.sewa.index', compact('sewas', 'units', 'pendapatanHariIni'));
    }

    public function show($id)
    {
        $sewa = Sewa::with(['unitSewa.jenisUnit', 'user', 'paket', 'tambahDurasi'])
            ->findOrFail($id);

        return view('operational.sewa.show', compact('sewa'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_sewa' => 'required|in:extended',
        ]);

        $sewa = Sewa::findOrFail($id);

        $sewa->update([
            'status_sewa' => $request->status_sewa,
            'updated_by'  => auth()->id(),
            ]);

        return back()->with('success', 'Status sewa berhasil diupdate.');
    }

    Public function konfirmasiPembayaran($id)
    {
        $sewa = Sewa::findOrFail($id);
        
        if ($sewa->payment_status !== 'unpaid' || $sewa->status_sewa !== 'pending') {
        return back()->with('error', 'Konfirmasi pembayaran tidak valid.');
        
        }
        
        $sewa->update([
        'payment_status' => 'paid',
        'status_sewa'    => 'disewa',
        'updated_by'     => auth()->id(),
        ]);
        
        return back()->with('success', 'Pembayaran dikonfirmasi.');
    }

    public function cancel($id)
    {
        $sewa = Sewa::findOrFail($id);
        
        if ($sewa->status_sewa !== 'pending') {
        return back()->with('error', 'Hanya sewa berstatus pending yang bisa dibatalkan.');
        }
        
        $sewa->update([
            'status_sewa' => 'cancelled',
            'updated_by'  => auth()->id(),
            ]);

            // Kalau sewa cancel unit kembali available
            UnitSewa::find($sewa->unit_id)->update(['status' => 'available']);
        
        return back()->with('success', 'Sewa berhasil dibatalkan.');
    }

    public function complete(Request $request, $id)
    {
        $request->validate([
            'jaminan_balik' => 'required|integer|min:0',
            'keterangan'    => 'nullable|string',
        ]);

        $sewa = Sewa::findOrFail($id);

        $sewa->update([
            'status_sewa'   => 'completed',
            'jaminan_balik' => $request->jaminan_balik,
            'keterangan'    => $request->keterangan,
            'updated_by'    => auth()->id(),
        ]);

        // Unit kembali available
        UnitSewa::find($sewa->unit_id)->update(['status' => 'available']);

        return back()->with('success', 'Sewa berhasil diselesaikan.');
    }

        public function cancelled($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status_booking !== 'pending') {
            return back()->with('error', 'Hanya booking berstatus pending yang bisa dibatalkan.');
        }

        $booking->update([
            'status_booking' => 'cancelled',
            'updated_by'     => auth()->id(),
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function destroy($id)
    {
        $sewa = Sewa::findOrFail($id);
        $sewa->delete();

        return redirect()->route('operational.sewa.index')
            ->with('success', 'Data sewa berhasil dihapus.');
    }
}