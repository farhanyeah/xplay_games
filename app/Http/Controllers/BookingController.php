<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\UnitBooking;
use App\Models\PaketHargaBooking;
use App\Models\PaketKhususBooking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $units = UnitBooking::with('jenisUnit')->get();
        $paketHarga = PaketHargaBooking::with('jenisUnit')->orderBy('jumlah_jam')->get();
        $paketKhusus = PaketKhususBooking::with('jenisUnit')->get();

        $perPage = 10;

        // ===== ANTRIAN BOOKING (Semua yang booked, hari ini + depan) =====
        $halamanAntrian = $request->filled('halaman_antrian') ? (int)$request->halaman_antrian : 1;
        
        $queryAntrian = Booking::with('unit.jenisUnit')
            ->whereIn('status_booking', ['booked'])
            ->where('tanggal', '>=', today())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai');

        $totalAntrian = $queryAntrian->count();
        $totalHalamanAntrian = ceil($totalAntrian / $perPage);
        $offsetAntrian = ($halamanAntrian - 1) * $perPage;
        $antrianBooking = $queryAntrian->skip($offsetAntrian)->take($perPage)->get();

        // ===== BOOKING SAYA (Customer yang login) =====
        $halaman = $request->filled('halaman') ? (int)$request->halaman : 1;
        
        $queryBooking = Booking::with('unit.jenisUnit', 'paket', 'paketKhusus')
            ->where(function($q) {
                $q->where('user_id', auth()->id())
                ->orWhere('created_by', auth()->id());
            })
            ->orderBy('created_at', 'desc');

        $totalBooking = $queryBooking->count();
        $totalHalaman = ceil($totalBooking / $perPage);
        $offset = ($halaman - 1) * $perPage;
        $bookingSaya = $queryBooking->skip($offset)->take($perPage)->get();

        // Pendapatan hari ini
        $pendapatanHariIni = Booking::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('harga');

        return view('pages.booking', compact(
            'units',
            'paketHarga',
            'paketKhusus',
            'antrianBooking',
            'bookingSaya',
            'pendapatanHariIni',
            'halaman',
            'totalHalaman',
            'totalBooking',
            'halamanAntrian',
            'totalHalamanAntrian',
            'totalAntrian'
        ));
    }

    public function store(Request $request)
    {
        $request->merge(['no_hp' => preg_replace('/[^0-9]/', '', $request->no_hp)]);
        
        $request->validate([
            'nama'              => 'required|string|max:255',
            'no_hp'             => ['required', 'min:10', 'max:15', 'regex:/^08[0-9]+$/'],
            'unit_id'           => 'required|exists:units_booking,id',
            'tanggal'           => 'required|date|after_or_equal:today',
            'tipe_booking'      => 'required|in:per_jam,happy_hour,paket_pagi,paket_malam',
            'paket_id'          => 'required_if:tipe_booking,per_jam|nullable|exists:paket_harga_booking,id',
            'paket_khusus_id'   => 'required_if:tipe_booking,happy_hour,paket_pagi,paket_malam|nullable|exists:paket_khusus_booking,id',
            'jam_mulai'         => 'required',
            'pembayaran'        => auth()->user()->role === 'customer' ? 'nullable|in:midtrans' : 'nullable|in:cash',
        ]);
        
        $unit = UnitBooking::with('jenisUnit')->findOrFail($request->unit_id);
        
        if ($request->tipe_booking === 'per_jam') {
            $paket = PaketHargaBooking::findOrFail($request->paket_id);
            $jumlahJam = $paket->jumlah_jam;
            $harga = $paket->harga;
            $paketId = $paket->id;
            $paketKhususId = null;
        } else {
            $paketKhusus = PaketKhususBooking::findOrFail($request->paket_khusus_id);
            $jumlahJam = $paketKhusus->jumlah_jam;
            $harga = $paketKhusus->harga;
            $paketId = null;
            $paketKhususId = $paketKhusus->id;
        }

        $jamMulai = \Carbon\Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
        $jamSelesai = $jamMulai->copy()->addHours($jumlahJam);

        // ========== CONFIG ==========
        $CurrentJam = (int) now()->format('H');
        $jamBuka = 10;
        $jamTutup = 3;

        // ===== 1. CEK JAM OPERASIONAL =====
        if ($CurrentJam >= 3 && $CurrentJam < 10) {
            return response()->json(['errors' => ['jam_mulai' => ['Tidak bisa booking karena XPLAY sedang tutup (buka jam 10:00).']]], 422);
        }

        // ===== 2. CEK JAM MULAI ======
        if ($jamMulai->lt(now())) {
            return response()->json(['errors' => ['jam_mulai' => ['Tidak bisa booking untuk jam yang sudah lewat.']]], 422);
        }

        // ===== 3. VALIDASI PAKET KHUSUS ======
        if ($request->tipe_booking !== 'per_jam') {
            $jamMulaiStr = $jamMulai->format('H:i');
            $jamMulaiInt = (int) $jamMulai->format('H');
            
            // Happy Hour Senin-Jumat
            if ($request->tipe_booking === 'happy_hour') {
                $hariIni = \Carbon\Carbon::parse($request->tanggal)->dayOfWeekIso;
                $hariBerlaku = $paketKhusus->hari_berlaku;
                
                if (!in_array($hariIni, $hariBerlaku)) {
                    return response()->json(['errors' => ['tanggal' => ['Happy Hour hanya berlaku Senin-Jumat.']]], 422);
                }
            }
            
            // ===== PAKET PAGI: MAX MULAI JAM 13:00 =====
            if ($request->tipe_booking === 'paket_pagi') {
                if ($jamMulaiInt > 13) {
                    return response()->json(['errors' => ['jam_mulai' => ['Paket Pagi hanya bisa dimulai maksimal jam 13:00.']]], 422);
                }
            }
            
            // ===== PAKET MALAM: MAX MULAI JAM 22:00 =====
            if ($request->tipe_booking === 'paket_malam') {
                if ($jamMulaiInt > 22) {
                    return response()->json(['errors' => ['jam_mulai' => ['Paket Malam hanya bisa dimulai maksimal jam 22:00.']]], 422);
                }
            }
        }

        // ===== 4. CEK JAM SELESAI (MAX 03:00) ======
        if (!in_array($request->tipe_booking, ['paket_pagi', 'paket_malam'])) {
            $jamSelesaiHour = (int) $jamSelesai->format('H');
            
            if ($jamSelesaiHour > 3 || ($jamSelesaiHour == 3 && $jamSelesai->minute > 0)) {
                return response()->json(['errors' => ['jam_mulai' => ['Booking maksimal sampai jam 03:00.']]], 422);
            }
        }
        
        // ===== 5. BENTROK ======
        $bentrok = Booking::where('unit_id', $request->unit_id)
            ->whereDate('tanggal', $request->tanggal)
            ->whereIn('status_booking', ['pending', 'booked'])
            ->where(function ($q) use ($jamSelesai, $request) {
                $q->where('jam_mulai', '<', $jamSelesai->format('H:i:s'))
                ->where('jam_selesai', '>', $request->jam_mulai);
            })->exists();
            
        if ($bentrok) {
            return response()->json(['errors' => ['jam_mulai' => ['Unit sudah dibooking pada jam tersebut.']]], 422);
        }
        
        // Simpan
        $transactionCode = 'BOOKING-' . now()->format('ymd') . '-' . strtoupper(uniqid());
        $pembayaran = auth()->user()->role === 'customer' ? 'midtrans' : 'cash';
        
        $booking = Booking::create([
            'transaction_code'   => $transactionCode,
            'user_id'           => auth()->user()->role === 'customer' ? auth()->id() : null,
            'created_by'         => auth()->id(),
            'updated_by'         => auth()->id(),
            'unit_id'           => $request->unit_id,
            'paket_id'          => $paketId,
            'paket_khusus_id'   => $paketKhususId,
            'nama'              => $request->nama,
            'no_hp'             => $request->no_hp,
            'tanggal'           => $request->tanggal,
            'jam_mulai'         => $jamMulai->format('H:i:s'),
            'jam_selesai'       => $jamSelesai->format('H:i:s'),
            'jumlah_jam'        => $jumlahJam,
            'harga'             => $harga,
            'pembayaran'        => $pembayaran,
            'status_booking'   => $pembayaran === 'cash' ? 'booked' : 'pending',
            'payment_status'    => $pembayaran === 'cash' ? 'paid' : 'unpaid',
        ]);
        
        if ($pembayaran === 'cash') {
            return response()->json(['message' => 'Booking berhasil dibuat.', 'booking_id' => $booking->id]);
        }
        
        // Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $orderId = 'BOOKING-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => ['order_id' => $orderId, 'gross_amount' => $harga],
            'customer_details' => ['first_name' => $request->nama, 'email' => auth()->user()->email, 'phone' => $request->no_hp],
            'item_details' => [['id' => 'BOOKING-' . $booking->id, 'price' => $harga, 'quantity' => 1, 'name' => 'Booking ' . $unit->kode_unit]],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $booking->update(['midtrans_token' => $snapToken, 'midtrans_order_id' => $orderId]);
            return response()->json(['snap_token' => $snapToken, 'booking_id' => $booking->id]);
        } catch (\Exception $e) {
            $booking->delete();
            return response()->json(['error' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function konfirmasiMidtrans(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->payment_status === 'paid') {
            return response()->json(['success' => true]);
        }
        $booking->update(['status_booking' => 'booked', 'payment_status' => 'paid']);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->payment_status === 'paid') {
            return response()->json(['error' => 'Tidak bisa menghapus booking yang sudah dibayar.'], 403);
        }
        $booking->forceDelete();
        return response()->json(['success' => true]);
    }
}