<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\UnitBooking;
use Illuminate\Http\Request;

class BookingOperasionalController extends Controller
{
        public function index(Request $request)
    {
        // Default: hari ini kalau ga ada tanggal
        if (!$request->filled('tanggal')) {
            $request->merge(['tanggal' => now()->toDateString()]);
        }

        $query = Booking::with(['unit.jenisUnit', 'paket', 'paketKhusus'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status_booking', $request->status);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_code', 'like', '%' . $request->search . '%')
                ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }

        $bookings = $query->paginate(10)->withQueryString();
        $units = UnitBooking::with('jenisUnit')->get();

        $pendapatanHariIni = Booking::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('harga');

        return view('operational.booking.index', compact('bookings', 'units', 'pendapatanHariIni'));
    }

    public function show($id)
    {
        $booking = Booking::with(['unit.jenisUnit', 'paket', 'paketKhusus'])
            ->findOrFail($id);

        return view('operational.booking.show', compact('booking'));
    }

    public function konfirmasiPembayaran($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->payment_status !== 'unpaid' || $booking->status_booking !== 'pending') {
            return back()->with('error', 'Konfirmasi pembayaran tidak valid.');
        }

        $booking->update([
            'payment_status' => 'paid',
            'status_booking' => 'booked',
            'pembayaran'     => 'cash',
            'updated_by'     => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran dikonfirmasi.');
    }

    public function selesaikan($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array($booking->status_booking, ['booked'])) {
            return back()->with('error', 'Hanya booking berstatus booked yang bisa diselesaikan.');
        }

        $booking->update([
            'status_booking' => 'done',
            'updated_by'     => auth()->id(),
        ]);

        return back()->with('success', 'Booking berhasil diselesaikan.');
    }

        public function cancel($id)
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
        $booking = Booking::findOrFail($id);
        $booking->forceDelete();

        return redirect()->route('operational.booking.index')
            ->with('success', 'Data booking berhasil dihapus.');
    }
}