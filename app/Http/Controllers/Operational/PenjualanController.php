<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        $query = Penjualan::with(['items', 'createdBy'])
            ->whereDate('created_at', $tanggal);

        if ($request->search) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('nama_item', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->metode) {
            $query->where('metode_pembayaran', $request->metode);
        }

        if ($request->status) {
            $query->where('payment_status', $request->status);
        }

        $penjualans = $query->orderBy('created_at', 'desc')->paginate(10);

        $pendapatanHariIni = Penjualan::whereDate('created_at', Carbon::today())
            ->where('payment_status', 'paid')
            ->sum('total_harga');

        $stoks = Stok::where('stok', '>', 0)->orderBy('kategori')->orderBy('nama')->get();

        return view('operational.penjualan', compact(
            'penjualans',
            'pendapatanHariIni',
            'stoks',
            'tanggal'
        ));
    }

 public function store(Request $request)
    {
        $request->validate([
            'items'               => 'required|array|min:1',
            'items.*.stok_id'     => 'required|exists:stok,id',
            'items.*.jumlah'      => 'required|integer|min:1',
            'metode_pembayaran'   => 'required|in:cash,midtrans',
        ]);

        // =========================
        // VALIDASI STOK (SEBELUM TRANSACTION)
        // =========================
        foreach ($request->items as $item) {
            $stok = Stok::find($item['stok_id']);

            if (!$stok) {
                return back()->with('error', 'Item tidak ditemukan.');
            }

            if ($stok->stok < $item['jumlah']) {
                return back()->with('error', 'Stok ' . $stok->nama . ' tidak mencukupi. Sisa stok: ' . $stok->stok);
            }
        }

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $itemsData  = [];

            foreach ($request->items as $item) {
                $stok = Stok::findOrFail($item['stok_id']);

                $subtotal    = $stok->harga * $item['jumlah'];
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'stok_id'   => $stok->id,
                    'nama_item' => $stok->nama,
                    'harga'     => $stok->harga,
                    'jumlah'    => $item['jumlah'],
                    'subtotal'  => $subtotal,
                ];
            }

            // =========================
            // CREATE PENJUALAN
            // =========================
            $transactionCode = 'PJL-' . strtoupper(uniqid());

            $penjualan = Penjualan::create([
                'transaction_code'  => $transactionCode,
                'created_by'        => Auth::id(),
                'total_harga'       => $totalHarga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'payment_status'    => $request->metode_pembayaran === 'cash' ? 'unpaid' : 'unpaid',
            ]);

            // =========================
            // SAVE ITEMS + REDUCE STOCK
            // =========================
            foreach ($itemsData as $itemData) {
                $penjualan->items()->create($itemData);
                Stok::where('id', $itemData['stok_id'])
                    ->decrement('stok', $itemData['jumlah']);
            }

            // =========================
            // MIDTRANS FLOW
            // =========================
            if ($request->metode_pembayaran === 'midtrans') {

                \Midtrans\Config::$serverKey    = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

                $midtransParams = [
                    'transaction_details' => [
                        'order_id'     => $transactionCode,
                        'gross_amount' => $totalHarga,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name ?? 'Customer',
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($midtransParams);

                $penjualan->update([
                    'midtrans_token'    => $snapToken,
                    'midtrans_order_id' => $transactionCode,
                ]);

                DB::commit();

                // IMPORTANT: return JSON untuk JS Snap
                return response()->json([
                    'success'     => true,
                    'snap_token'  => $snapToken,
                    'penjualan_id'=> $penjualan->id
                ]);
            }

            // =========================
            // CASH FLOW
            // =========================
            DB::commit();

            return redirect()
                ->route('operational.penjualan')
                ->with('success', 'Transaksi cash berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }   

    public function konfirmasiPembayaran(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        
        if ($penjualan->payment_status === 'paid') {
            return redirect()->route('operational.penjualan')
            ->with('error', 'Transaksi ini sudah dikonfirmasi sebelumnya.');
        }

        $penjualan->update(['payment_status' => 'paid']);

        return redirect()->route('operational.penjualan')
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::with('items')->findOrFail($id);

            // Kembalikan stok
            foreach ($penjualan->items as $item) {
                if ($item->stok_id) {
                    Stok::find($item->stok_id)?->increment('stok', $item->jumlah);
                }
            }

            $penjualan->delete();

            DB::commit();

            return redirect()->route('operational.penjualan')
                ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}