<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use App\Models\TambahDurasi;
use App\Models\PaketHargaSewa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class TambahDurasiController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'tambah_hari' => 'required|integer|min:1',
        ]);

        $sewa = Sewa::with(['unitSewa.jenisUnit', 'user'])->findOrFail($id);

        // Cek kepemilikan — user_id harus cocok dengan yang login
        // Kalau user_id null (walk-in), tidak bisa tambah durasi dari sini
        if ($sewa->user_id === null || $sewa->user_id !== auth()->id()) {
        return response()->json(['error' => 'Anda tidak memiliki akses ke transaksi ini.'], 403);
}

        // Pastikan status sewa valid
        if (!in_array($sewa->status_sewa, ['disewa', 'extended'])) {
            return response()->json(['error' => 'Status sewa tidak valid.'], 422);
        }

        // Hapus record unpaid lama kalau ada
        TambahDurasi::where('sewa_id', $sewa->id)
            ->where('payment_status', 'unpaid')
            ->delete();

        // Cari harga 1 hari
        $paket1Hari = PaketHargaSewa::where('jenis_unit_id', $sewa->unitSewa->jenis_unit_id)
            ->where('durasi_hari', 1)
            ->firstOrFail();

        $hargaTambah = $paket1Hari->harga * $request->tambah_hari;

        // Setup Midtrans
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        $orderId = 'TD-' . $sewa->id . '-' . time();

        // Buat record tambah durasi
        $tambahDurasi = TambahDurasi::create([
            'sewa_id'        => $sewa->id,
            'tambah_hari'    => $request->tambah_hari,
            'harga_tambah'   => $hargaTambah,
            'payment_status' => 'unpaid',
            'created_by'     => auth()->id(),
            'midtrans_order_id' => $orderId,
        ]);

        // Generate Snap Token
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $hargaTambah,
            ],
            'customer_details' => [
                'first_name' => $sewa->nama,
                'phone'      => $sewa->no_hp,
                'email'      => $sewa->user ? $sewa->user->email : '',
                'billing_address' => [ 
                        'address' => $sewa->alamat,
                    ],
            ],
            'item_details' => [[
                'id'       => 'TAMBAH-DURASI-' . $sewa->id,
                'price'    => $hargaTambah,
                'quantity' => 1,
                'name'     => 'Perpanjang Sewa ' . $request->tambah_hari . ' Hari - ' . $sewa->transaction_code,
            ]],
        ];

        try {
    $snapToken = Snap::getSnapToken($params);
} catch (\Exception $e) {
    $tambahDurasi->delete();
    return response()->json([
        'error' => 'Gagal generate token Midtrans: ' . $e->getMessage()
    ], 500);
}

$tambahDurasi->update(['midtrans_token' => $snapToken]);

        return response()->json([
            'snap_token'       => $snapToken,
            'tambah_durasi_id' => $tambahDurasi->id,
            'harga_tambah'     => $hargaTambah,
            'tambah_hari'      => $request->tambah_hari,
        ]);
    }

    public function konfirmasi(Request $request, $id)
{
    $tambahDurasi = TambahDurasi::with('sewa')->findOrFail($id);

    // Tambah ini — skip kalau sudah paid agar tidak double update
    if ($tambahDurasi->payment_status === 'paid') {
        return response()->json(['success' => true]);
    }

    $sewa = $tambahDurasi->sewa;

    $tambahDurasi->update(['payment_status' => 'paid']);

    $tanggalSelesaiBaru = Carbon::parse($sewa->tanggal_selesai)
        ->addDays($tambahDurasi->tambah_hari);

    $sewa->update([
        'tanggal_selesai' => $tanggalSelesaiBaru,
        'status_sewa'     => 'extended',
        'total_harga'     => $sewa->total_harga + $tambahDurasi->harga_tambah,
        'updated_by'      => auth()->id(),
    ]);

    return response()->json(['success' => true]);
}

public function destroy($id)
{
    $tambahDurasi = TambahDurasi::findOrFail($id);
    
    // Pastikan hanya yang unpaid yang bisa dihapus
    if ($tambahDurasi->payment_status === 'paid') {
        return response()->json(['error' => 'Tidak bisa menghapus transaksi yang sudah dibayar.'], 403);
    }
    
    $tambahDurasi->delete();
    return response()->json(['success' => true]);
}
}