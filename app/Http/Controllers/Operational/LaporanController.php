<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Laporan;
use App\Models\Billing;
use App\Models\Sewa;
use App\Models\Booking;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default: hari ini
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();
        
        $kemarin = Carbon::parse($tanggal)->subDay()->toDateString();

        // ========== AMBIL PENDAPATAN DARI DATABASE ==========
        
        // Billing: status_sesi = completed, status_bayar = paid, berdasarkan updated_at
        $pendapatanBilling = Billing::where('status_sesi', 'completed')
            ->where('status_bayar', 'paid')
            ->whereDate('updated_at', $tanggal)
            ->sum('harga_final');

        // Sewa: payment_status = paid, berdasarkan created_at
        $pendapatanSewa = Sewa::whereDate('created_at', $tanggal)
            ->where('payment_status', 'paid')
            ->sum('total_harga');

        // Booking: payment_status = paid, berdasarkan created_at
        $pendapatanBooking = Booking::whereDate('created_at', $tanggal)
            ->where('payment_status', 'paid')
            ->sum('harga');

        // Penjualan: payment_status = paid, berdasarkan created_at
        $pendapatanPenjualan = Penjualan::whereDate('created_at', $tanggal)
            ->where('payment_status', 'paid')
            ->sum('total_harga');

        // Total pendapatan
        $totalPendapatan = $pendapatanBilling + $pendapatanSewa + $pendapatanBooking + $pendapatanPenjualan;

        // ========== BUKA KAS (DARI DATA KEMARIN) ==========
        $kasKemarin = Kas::where('tanggal', $kemarin)->first();
        $bukaKas = $kasKemarin ? $kasKemarin->saldo_akhir : 0;

        // ========== CEK APAKAH SUDAH ADA LAPORAN ==========
        $laporanExists = Laporan::where('tanggal', $tanggal)->exists();

        return view('operational.laporan.laporan', compact(
            'tanggal',
            'pendapatanBilling',
            'pendapatanSewa',
            'pendapatanBooking',
            'pendapatanPenjualan',
            'totalPendapatan',
            'bukaKas',
            'laporanExists'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'buka_kas' => 'required|numeric|min:0',
            'pengeluaran_part_time' => 'nullable|numeric|min:0',
            'pengeluaran_gestun' => 'nullable|numeric|min:0',
            'pengeluaran_lain' => 'nullable|numeric|min:0',
            'keterangan_pengeluaran' => 'required_if:pengeluaran_lain,>0|string|nullable',
            'saldo_midtrans' => 'nullable|numeric|min:0',
        ]);

        $tanggal = $request->tanggal;

        // ========== AMBIL PENDAPATAN ==========
        
        $pendapatanBilling = Billing::where('status_sesi', 'completed')
            ->where('status_bayar', 'paid')
            ->whereDate('updated_at', $tanggal)
            ->sum('harga_final');

        $pendapatanSewa = Sewa::whereDate('created_at', $tanggal)
            ->where('payment_status', 'paid')
            ->sum('total_harga');

        $pendapatanBooking = Booking::whereDate('created_at', $tanggal)
            ->where('payment_status', 'paid')
            ->sum('harga');

        $pendapatanPenjualan = Penjualan::whereDate('created_at', $tanggal)
            ->where('payment_status', 'paid')
            ->sum('total_harga');

        $totalPendapatan = $pendapatanBilling + $pendapatanSewa + $pendapatanBooking + $pendapatanPenjualan;

        // ========== PENGELUARAN ==========
        $pengeluaranPartTime = $request->pengeluaran_part_time ?? 0;
        $pengeluaranGestun = $request->pengeluaran_gestun ?? 0;
        $pengeluaranLain = $request->pengeluaran_lain ?? 0;
        $keteranganPengeluaran = $request->keterangan_pengeluaran;

        $totalPengeluaran = $pengeluaranPartTime + $pengeluaranGestun + $pengeluaranLain;

        // ========== SALDO MIDTRANS ==========
        $saldoMidtrans = $request->saldo_midtrans ?? 0;

        // ========== HITUNG TUTUP KAS ==========
        $bukaKas = $request->buka_kas;
        $tutupKas = $bukaKas + $totalPendapatan - $totalPengeluaran - $saldoMidtrans;

        // ========== SIMPAN LAPORAN ==========
        Laporan::create([
            'tanggal' => $tanggal,
            'pendapatan_billing' => $pendapatanBilling,
            'pendapatan_sewa' => $pendapatanSewa,
            'pendapatan_booking' => $pendapatanBooking,
            'pendapatan_penjualan' => $pendapatanPenjualan,
            'total_pendapatan' => $totalPendapatan,
            'pengeluaran_part_time' => $pengeluaranPartTime,
            'pengeluaran_gestun' => $pengeluaranGestun,
            'pengeluaran_lain' => $pengeluaranLain,
            'keterangan_pengeluaran' => $keteranganPengeluaran,
            'saldo_midtrans' => $saldoMidtrans,
            'buka_kas' => $bukaKas,
            'tutup_kas' => $tutupKas,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // ========== SIMPAN/UPDATE KAS ==========
        Kas::updateOrCreate(
            ['tanggal' => $tanggal],
            [
                'saldo_awal' => $bukaKas,
                'saldo_akhir' => $tutupKas,
                'keterangan' => 'Closing otomatis dari laporan',
            ]
        );

        return redirect()->route('operational.laporan', ['tanggal' => $tanggal])
            ->with('success', 'Laporan berhasil digenerate!');
    }

    
    public function exportPdf(Request $request, $tanggal)
    {
        $laporan = Laporan::with(['createdBy', 'updatedBy'])
            ->where('tanggal', $tanggal)
            ->firstOrFail();

        $pdf = PDF::loadView('operational.laporan.laporan-pdf', compact('laporan'));
        
        return $pdf->download('Laporan-XPLAY-' . $tanggal . '.pdf');
    }

    public function riwayat(Request $request)
    {
        $query = Laporan::with('createdBy')
            ->orderBy('tanggal', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        $laporans = $query->paginate(10)->withQueryString();
        $tanggal = $request->tanggal ?? now()->toDateString();

        return view('operational.laporan.riwayat', compact('laporans', 'tanggal'));
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus. Silakan generate ulang.');
    }
}