<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\JenisUnit;
use App\Models\JenisUnitBooking;
use App\Models\PaketHargaSewa;
use App\Models\PaketHargaBooking;
use App\Models\PaketKhususBooking;
use Illuminate\Http\Request;

class KelolaHargaController extends Controller
{
        public function index(Request $request)
    {
        $jenisUnitSewa    = JenisUnit::all();
        $jenisUnitBooking = JenisUnitBooking::all();

        $namaPaketKhusus = PaketKhususBooking::select('nama_paket')
        ->distinct()
        ->orderBy('nama_paket')
        ->get();

        // Filter Paket Sewa
        $querySewa = PaketHargaSewa::with('jenisUnit');
        if ($request->filter_jenis_sewa) {
            $querySewa->where('jenis_unit_id', $request->filter_jenis_sewa);
        }
        $paketSewa = $querySewa->orderBy('jenis_unit_id')->orderBy('durasi_hari')->get();

        // Filter Paket Booking
        $queryBooking = PaketHargaBooking::with('jenisUnit');
        if ($request->filter_jenis_booking) {
            $queryBooking->where('jenis_unit_id', $request->filter_jenis_booking);
        }
        $paketBooking = $queryBooking->orderBy('jenis_unit_id')->orderBy('jumlah_jam')->get();

        // Filter Paket Khusus
        $queryKhusus = PaketKhususBooking::with('jenisUnit');
        if ($request->filter_jenis_khusus) {
            $queryKhusus->where('jenis_unit_id', $request->filter_jenis_khusus);
        }
        if ($request->filter_nama_khusus) {
            $queryKhusus->where('nama_paket', $request->filter_nama_khusus);
        }
        $paketKhusus = $queryKhusus->orderBy('jenis_unit_id')->get();

        return view('operational.kelola-harga', compact(
            'jenisUnitSewa',
            'jenisUnitBooking',
            'namaPaketKhusus',
            'paketSewa',
            'paketBooking',
            'paketKhusus'
        ));
    }

    // =========================================
    // PAKET HARGA SEWA
    // =========================================

    public function storeSewa(Request $request)
    {
        $request->validate([
            'jenis_unit_id' => 'required|exists:jenis_unit,id',
            'durasi_hari'   => 'required|integer|min:1',
            'harga'         => 'required|integer|min:0',
        ]);

        PaketHargaSewa::create($request->only('jenis_unit_id', 'durasi_hari', 'harga'));

        return redirect()->route('operational.kelola-harga', ['tab' => 'sewa'])
            ->with('success', 'Paket harga sewa berhasil ditambahkan.');
    }

    public function updateSewa(Request $request, PaketHargaSewa $paket)
    {
        $request->validate([
            'jenis_unit_id' => 'required|exists:jenis_unit,id',
            'durasi_hari'   => 'required|integer|min:1',
            'harga'         => 'required|integer|min:0',
        ]);

        $paket->update($request->only('jenis_unit_id', 'durasi_hari', 'harga'));

        return redirect()->route('operational.kelola-harga', ['tab' => 'sewa'])
            ->with('success', 'Paket harga sewa berhasil diupdate.');
    }

    public function destroySewa(PaketHargaSewa $paket)
    {
        $paket->delete();

        return redirect()->route('operational.kelola-harga', ['tab' => 'sewa'])
            ->with('success', 'Paket harga sewa berhasil dihapus.');
    }

    // =========================================
    // PAKET HARGA BOOKING
    // =========================================

    public function storeBooking(Request $request)
    {
        $request->validate([
            'jenis_unit_id' => 'required|exists:jenis_unit_booking,id',
            'jumlah_jam'    => 'required|integer|min:1',
            'harga'         => 'required|integer|min:0',
        ]);

        PaketHargaBooking::create($request->only('jenis_unit_id', 'jumlah_jam', 'harga'));

        return redirect()->route('operational.kelola-harga', ['tab' => 'booking'])
            ->with('success', 'Paket harga booking berhasil ditambahkan.');
    }

    public function updateBooking(Request $request, PaketHargaBooking $paket)
    {
        $request->validate([
            'jenis_unit_id' => 'required|exists:jenis_unit_booking,id',
            'jumlah_jam'    => 'required|integer|min:1',
            'harga'         => 'required|integer|min:0',
        ]);

        $paket->update($request->only('jenis_unit_id', 'jumlah_jam', 'harga'));

        return redirect()->route('operational.kelola-harga', ['tab' => 'booking'])
            ->with('success', 'Paket harga booking berhasil diupdate.');
    }

    public function destroyBooking(PaketHargaBooking $paket)
    {
        $paket->delete();

        return redirect()->route('operational.kelola-harga', ['tab' => 'booking'])
            ->with('success', 'Paket harga booking berhasil dihapus.');
    }

    // =========================================
    // PAKET KHUSUS BOOKING
    // =========================================

    public function storeKhusus(Request $request)
    {
        $request->validate([
            'jenis_unit_id'      => 'required|exists:jenis_unit_booking,id',
            'nama_paket'         => 'required|string|max:255',
            'jumlah_jam'         => 'required|integer|min:1',
            'harga'              => 'required|integer|min:0',
            'hari_berlaku'       => 'required|array|min:1',
            'jam_mulai_berlaku'  => 'required',
            'jam_selesai_berlaku'=> 'required',
        ]);

        PaketKhususBooking::create([
            'jenis_unit_id'       => $request->jenis_unit_id,
            'nama_paket'          => $request->nama_paket,
            'jumlah_jam'          => $request->jumlah_jam,
            'harga'               => $request->harga,
            'hari_berlaku'        => $request->hari_berlaku,
            'jam_mulai_berlaku'   => $request->jam_mulai_berlaku,
            'jam_selesai_berlaku' => $request->jam_selesai_berlaku,
        ]);

        return redirect()->route('operational.kelola-harga', ['tab' => 'khusus'])
            ->with('success', 'Paket khusus booking berhasil ditambahkan.');
    }

    public function updateKhusus(Request $request, PaketKhususBooking $paket)
    {
        $request->validate([
            'jenis_unit_id'      => 'required|exists:jenis_unit_booking,id',
            'nama_paket'         => 'required|string|max:255',
            'jumlah_jam'         => 'required|integer|min:1',
            'harga'              => 'required|integer|min:0',
            'hari_berlaku'       => 'required|array|min:1',
            'jam_mulai_berlaku'  => 'required',
            'jam_selesai_berlaku'=> 'required',
        ]);

        $paket->update([
            'jenis_unit_id'       => $request->jenis_unit_id,
            'nama_paket'          => $request->nama_paket,
            'jumlah_jam'          => $request->jumlah_jam,
            'harga'               => $request->harga,
            'hari_berlaku'        => $request->hari_berlaku,
            'jam_mulai_berlaku'   => $request->jam_mulai_berlaku,
            'jam_selesai_berlaku' => $request->jam_selesai_berlaku,
        ]);

        return redirect()->route('operational.kelola-harga', ['tab' => 'khusus'])
            ->with('success', 'Paket khusus booking berhasil diupdate.');
    }

    public function destroyKhusus(PaketKhususBooking $paket)
    {
        $paket->delete();

        return redirect()->route('operational.kelola-harga', ['tab' => 'khusus'])
            ->with('success', 'Paket khusus booking berhasil dihapus.');
    }
}