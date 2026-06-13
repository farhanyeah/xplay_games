<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use App\Models\UnitSewa;
use App\Models\PaketHargaSewa;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class SewaController extends Controller
{
    public function index(Request $request)
    {
        $units = UnitSewa::with('jenisUnit')->get();
        $paketHarga = PaketHargaSewa::with('jenisUnit')->orderBy('durasi_hari')->get();

        // ===== RIWAYAT PUBLIK (Sekarang dengan pagination) =====
        $perPage = 10;
        $halamanPublik = $request->filled('halaman_publik') ? (int)$request->halaman_publik : 1;
        
        $queryPublik = Sewa::with('unitSewa.jenisUnit')
            ->where('status_sewa', 'completed')
            ->orderBy('tanggal_selesai', 'desc');

        $totalPublik = $queryPublik->count();
        $totalHalamanPublik = ceil($totalPublik / $perPage);
        $offsetPublik = ($halamanPublik - 1) * $perPage;
        $riwayatPublik = $queryPublik->skip($offsetPublik)->take($perPage)->get();

        // ===== SEWA SAYA (Customer) =====
        $halaman = $request->filled('halaman') ? (int)$request->halaman : 1;
        
        $sewaQuery = Sewa::with('unitSewa.jenisUnit', 'paket')
            ->where(function($q) {
                $q->where('user_id', auth()->id())
                ->orWhere('created_by', auth()->id());
            })
            ->orderBy('created_at', 'desc');

        $totalSewa = $sewaQuery->count();
        $totalHalaman = ceil($totalSewa / $perPage);
        $offset = ($halaman - 1) * $perPage;
        $sewaSaya = $sewaQuery->skip($offset)->take($perPage)->get();

        return view('pages.sewa', compact(
            'units', 
            'paketHarga', 
            'riwayatPublik',
            'sewaSaya', 
            'halaman', 
            'totalHalaman', 
            'totalSewa',
            'halamanPublik',
            'totalHalamanPublik',
            'totalPublik'
        ));
    }

    public function store(Request $request)
    {
        // Bersihkan nomor HP
        $request->merge(['no_hp' => preg_replace('/[^0-9]/', '', $request->no_hp)]);

        // VALIDASI
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => ['required', 'min:10', 'max:15', 'regex:/^08[0-9]+$/'],
            'unit_id' => 'required|exists:units_sewa,id',
            'paket_id' => 'required',
            'pembayaran' => auth()->user()->role === 'customer' ? 'required|in:cash,midtrans' : 'nullable|in:cash',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'guarantee_type' => 'required|in:KTP,SIM,KTM,Lainnya',
            'alamat' => 'required|string',
            'durasi_custom' => $request->paket_id === 'lainnya' ? 'required|integer|min:1' : 'nullable',
        ]);

        // Format Transaction Code
        $transactionCode = 'XPG-' . now()->format('ymd') . '-' . strtoupper(uniqid());

        // HITUNG HARGA
        $unit = UnitSewa::with('jenisUnit')->findOrFail($request->unit_id);
        $hargaJaminan = $unit->jenisUnit->harga_jaminan;

        if ($request->paket_id === 'lainnya') {
            $paket1Hari = PaketHargaSewa::where('jenis_unit_id', $unit->jenis_unit_id)
                ->where('durasi_hari', 1)->firstOrFail();
            $hargaSewa = $paket1Hari->harga * $request->durasi_custom;
            $paketId = null;
        } else {
            $paket = PaketHargaSewa::findOrFail($request->paket_id);
            $hargaSewa = $paket->harga;
            $paketId = $paket->id;
        }
        $totalHarga = $hargaSewa + $hargaJaminan;

        // VALIDASI BENTROK
        $unitBentrok = Sewa::where('unit_id', $request->unit_id)
            ->whereIn('status_sewa', ['pending', 'disewa'])
            ->where(function ($q) use ($request) {
                $q->where('tanggal_mulai', '<=', $request->tanggal_selesai)
                    ->where('tanggal_selesai', '>=', $request->tanggal_mulai);
            })->exists();

        if ($unitBentrok) {
            return back()->withErrors(['unit_id' => 'Unit PlayStation sedang disewa pada tanggal tersebut.'])->withInput();
        }

        // SIMPAN DATA
        $sewa = Sewa::create([
            'transaction_code' => $transactionCode,
            'user_id' => auth()->user()->role === 'customer' ? auth()->id() : null,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'unit_id' => $request->unit_id,
            'paket_id' => $paketId,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'pembayaran' => auth()->user()->role === 'customer' ? $request->pembayaran : 'cash',
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'guarantee_type' => $request->guarantee_type,
            'guarantee_other' => $request->guarantee_type === 'Lainnya' ? $request->guarantee_other : null,
            'durasi_custom' => $request->paket_id === 'lainnya' ? $request->durasi_custom : null,
            'harga_sewa' => $hargaSewa,
            'harga_jaminan' => $hargaJaminan,
            'total_harga' => $totalHarga,
            'status_sewa' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // ===== CASH =====
        if ($request->pembayaran === 'cash') {
            $unit->update(['status' => 'not_available']);
            return response()->json(['message' => 'Sewa Berhasil dibuat', 'sewa_id' => $sewa->id]);
        }

        // ===== MIDTRANS =====
        if ($request->pembayaran === 'midtrans') {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            $orderId = 'SEWA-' . $sewa->id . '-' . time();
            $params = [
                'transaction_details' => ['order_id' => $orderId, 'gross_amount' => $totalHarga],
                
                'customer_details' => [
                    'first_name' => $request->nama,
                    'email' => auth()->user()->email,
                    'phone' => $request->no_hp,
                    'billing_address' => [ 
                        'address' => $request->alamat,
                    ],
                ],
                'item_details' => [
                    ['id' => 'SEWA-' . $sewa->id, 'price' => $hargaSewa, 'quantity' => 1, 'name' => 'Sewa PlayStation'],
                    ['id' => 'JAMINAN-' . $sewa->id, 'price' => $hargaJaminan, 'quantity' => 1, 'name' => 'Jaminan PlayStation'],
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                $sewa->update(['midtrans_token' => $snapToken, 'midtrans_order_id' => $orderId]);

                $unit->update(['status' => 'not_available']);

                return response()->json(['snap_token' => $snapToken, 'sewa_id' => $sewa->id]);
            } catch (\Exception $e) {
                $sewa->delete();
                return response()->json(['error' => 'Gagal: ' . $e->getMessage()], 500);
            }
        }
    }

    // ===== KONFIRMASI MIDTRANS =====
public function konfirmasiMidtrans(Request $request, $id)
{
    $sewa = Sewa::findOrFail($id);

    // Cek dulu, kalau sudah paid skip agar tidak double update
    if ($sewa->payment_status === 'paid') {
        return response()->json(['success' => true]);
    }

    $sewa->update(['status_sewa' => 'disewa', 'payment_status' => 'paid']);
    UnitSewa::where('id', $sewa->unit_id)->update(['status' => 'not_available']);
    return response()->json(['success' => true]);
}

    public function destroy($id)
    {
        $sewa = Sewa::findOrFail($id);
        UnitSewa::where('id', $sewa->unit_id)->update(['status' => 'available']);
        $sewa->delete();
        return response()->json(['success' => true]);
    }
}