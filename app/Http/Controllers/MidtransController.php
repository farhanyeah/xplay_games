<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sewa;
use App\Models\TambahDurasi;
use App\Models\UnitSewa;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Billing;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        try {

            \Log::info('MIDTRANS WEBHOOK MASUK', $request->all());

            $orderId = $request->order_id;
            $transactionStatus = $request->transaction_status;

            /*
            |--------------------------------------------------------------------------
            | Validasi Signature Midtrans
            |--------------------------------------------------------------------------
            */
            $serverKey = config('midtrans.server_key');

            $signature = hash(
                'sha512',
                $orderId .
                $request->status_code .
                $request->gross_amount .
                $serverKey
            );

            if ($signature !== $request->signature_key) {

                \Log::warning('MIDTRANS SIGNATURE INVALID', [
                    'order_id' => $orderId
                ]);

                return response()->json([
                    'message' => 'Invalid signature'
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | HANDLE TAMBAH DURASI
            |--------------------------------------------------------------------------
            */
            if (str_starts_with($orderId, 'TD-')) {

                $tambahDurasi = TambahDurasi::with('sewa')
                    ->where('midtrans_order_id', $orderId)
                    ->first();

                if (!$tambahDurasi) {
                    return response()->json([
                        'message' => 'TambahDurasi tidak ditemukan'
                    ], 404);
                }

                if (in_array($transactionStatus, ['capture', 'settlement'])) {

                    if ($tambahDurasi->payment_status === 'paid') {
                        return response()->json(['message' => 'OK']);
                    }

                    $sewa = $tambahDurasi->sewa;

                    $tambahDurasi->update([
                        'payment_status' => 'paid'
                    ]);

                    $tanggalSelesaiBaru = Carbon::parse($sewa->tanggal_selesai)
                        ->addDays($tambahDurasi->tambah_hari);

                    $sewa->update([
                        'tanggal_selesai' => $tanggalSelesaiBaru,
                        'status_sewa'     => 'extended',
                        'total_harga'     => $sewa->total_harga + $tambahDurasi->harga_tambah,
                    ]);
                }

                return response()->json(['message' => 'OK']);
            }

            /*
            |--------------------------------------------------------------------------
            | HANDLE BOOKING
            |--------------------------------------------------------------------------
            */
            if (str_starts_with($orderId, 'BOOKING-')) {

                $booking = Booking::where('midtrans_order_id', $orderId)->first();

                if (!$booking) {
                    return response()->json([
                        'message' => 'Booking tidak ditemukan'
                    ], 404);
                }

                if (in_array($transactionStatus, ['capture', 'settlement'])) {

                    if ($booking->payment_status !== 'paid') {

                        $booking->update([
                            'payment_status' => 'paid',
                            'status_booking' => 'booked',
                        ]);
                    }
                }

                if ($transactionStatus === 'pending') {

                    if ($booking->payment_status !== 'paid') {

                        $booking->update([
                            'payment_status' => 'unpaid',
                            'status_booking' => 'pending',
                        ]);
                    }
                }

                if (in_array($transactionStatus, ['expire', 'deny'])) {

                    if ($booking->payment_status !== 'paid') {
                        $booking->update([
                        'payment_status' => 'unpaid',
                        'status_booking' => 'cancelled',
                    ]);
                }

                }

                if ($transactionStatus === 'cancel') {

                    $booking->update([
                        'payment_status' => 'unpaid',
                        'status_booking' => 'cancelled',
                    ]);
                }

                return response()->json(['message' => 'OK']);
            }
                /*
                |--------------------------------------------------------------------------
                | HANDLE BILLING
                |--------------------------------------------------------------------------
                */
                if (str_starts_with($orderId, 'BILLING-')) {

                $billing = Billing::where('midtrans_order_id', $orderId)->first();

                if (!$billing) {
                    return response()->json([
                        'message' => 'Billing tidak ditemukan'
                        ], 404);
                    }

                if (in_array($transactionStatus, ['capture', 'settlement'])) {

                if ($billing->status_bayar !== 'paid') {
                    $billing->update([
                        'status_bayar' => 'paid',
                        ]);
                    }
                }

                if ($transactionStatus === 'pending') {

                if ($billing->status_bayar !== 'paid') {
                    $billing->update([
                        'status_bayar' => 'pending',
                        ]);
                    }
                }

                if (in_array($transactionStatus, ['expire', 'deny', 'cancel'])) {

                if ($billing->status_bayar !== 'paid') {
                    $billing->update([
                        'status_bayar' => 'pending',
                        ]);
                    }
                }

                return response()->json(['message' => 'OK']);
                }

            /*
            |--------------------------------------------------------------------------
            | HANDLE SEWA
            |--------------------------------------------------------------------------
            */
            $sewaId = explode('-', $orderId)[1] ?? null;

            $sewa = Sewa::find($sewaId);

            if (!$sewa) {
                return response()->json([
                    'message' => 'Sewa tidak ditemukan'
                ], 404);
            }

            if (in_array($transactionStatus, ['capture', 'settlement'])) {

                if ($sewa->payment_status !== 'paid') {

                    $sewa->update([
                        'payment_status' => 'paid',
                        'status_sewa'    => 'disewa',
                    ]);

                    UnitSewa::where('id', $sewa->unit_id)
                        ->update([
                            'status' => 'not_available'
                        ]);
                }
            }

            if ($transactionStatus === 'pending') {

                // Jangan timpa transaksi yang sudah paid
                if ($sewa->payment_status !== 'paid') {

                    $sewa->update([
                        'payment_status' => 'unpaid',
                        'status_sewa'    => 'pending',
                    ]);
                }
            }

            if (in_array($transactionStatus, ['expire', 'deny'])) {

                $sewa->update([
                    'payment_status' => 'unpaid',
                    'status_sewa'    => 'cancelled',
                ]);

                UnitSewa::where('id', $sewa->unit_id)
                    ->update([
                        'status' => 'available'
                    ]);
            }

            if ($transactionStatus === 'cancel') {

                $sewa->update([
                    'payment_status' => 'unpaid',
                    'status_sewa'    => 'cancelled',
                ]);

                UnitSewa::where('id', $sewa->unit_id)
                    ->update([
                        'status' => 'available'
                    ]);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {

            \Log::error('MIDTRANS WEBHOOK ERROR', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}