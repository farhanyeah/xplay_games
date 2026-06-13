<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingUnit;
use App\Models\JenisUnitBooking;
use App\Models\PaketHargaBooking;
use App\Models\PaketKhususBooking;
use Illuminate\Http\Request;
use App\Models\BillingExtend;
use App\Models\BillingRefund;
use App\Models\BillingPindahUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class BillingController extends Controller
{
    public function index()
    {

        $lantai1 = BillingUnit::with(['jenisUnit', 'activeBilling.paketHarga', 'activeBilling.paketKhusus', 'activeBilling.extends'])
            ->where('lantai', 1)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        $lantai2 = BillingUnit::with(['jenisUnit', 'activeBilling.paketHarga', 'activeBilling.paketKhusus', 'activeBilling.extends'])
            ->where('lantai', 2)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        $paketHarga = PaketHargaBooking::orderBy('jenis_unit_id')
            ->orderBy('jumlah_jam')
            ->get();

        $paketKhusus = PaketKhususBooking::orderBy('jenis_unit_id')
            ->get();

        return view('operational.billing.index', compact(
            'lantai1',
            'lantai2',
            'paketHarga',
            'paketKhusus'
        ));
    }

    public function getPaketHarga(Request $request)
    {
        $jenisUnitId = $request->jenis_unit_id;

        $paketHarga = PaketHargaBooking::where('jenis_unit_id', $jenisUnitId)
            ->orderBy('jumlah_jam')
            ->get();

        $paketKhusus = PaketKhususBooking::where('jenis_unit_id', $jenisUnitId)
            ->get();

        return response()->json([
            'paket_harga'  => $paketHarga,
            'paket_khusus' => $paketKhusus,
        ]);
    }

    private function setupMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    private function createBillingSnapToken(Billing $billing)
    {
        $this->setupMidtrans();

        $orderId = 'BILLING-' . $billing->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $billing->harga_final,
            ],
            'customer_details' => [
                'first_name' => $billing->nama_customer,
            ],
            'item_details' => [
                [
                    'id' => 'BILLING-' . $billing->id,
                    'price' => (int) $billing->harga_final,
                    'quantity' => 1,
                    'name' => 'Billing PlayStation - ' . $billing->nama_customer,
                ]
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $billing->update([
            'midtrans_order_id' => $orderId,
            'midtrans_token' => $snapToken,
            'updated_by' => Auth::id(),
        ]);

        return $snapToken;
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_unit_id' => 'required|exists:billing_units,id',
            'nama_customer'   => 'required|string|max:100',
            'jumlah_jam'      => 'required|integer|min:1|max:12',
            'paket_harga_id'  => 'nullable|exists:paket_harga_booking,id',
            'paket_khusus_id' => 'nullable|exists:paket_khusus_booking,id',
            'metode_bayar'    => 'required|in:cash,midtrans',
            'catatan'         => 'nullable|string|max:500',
        ]);

        if (!$request->paket_harga_id && !$request->paket_khusus_id) {
            return response()->json([
                'message' => 'Pilih salah satu paket harga atau paket khusus.'
            ], 422);
        }

        if ($request->paket_harga_id && $request->paket_khusus_id) {
            return response()->json([
                'message' => 'Tidak boleh memilih paket harga dan paket khusus sekaligus.'
            ], 422);
        }

        try {
            $billing = DB::transaction(function () use ($request) {
                $unit = BillingUnit::with('jenisUnit')
                    ->where('id', $request->billing_unit_id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (!$unit) {
                    throw new \Exception('Unit tidak ditemukan atau tidak aktif.');
                }

                $unitSibuk = Billing::where('billing_unit_id', $unit->id)
                    ->whereIn('status_sesi', ['available', 'active'])
                    ->lockForUpdate()
                    ->exists();

                if ($unitSibuk) {
                    throw new \Exception('Unit sedang digunakan atau di-hold.');
                }

                $harga = 0;
                $jumlahJam = $request->jumlah_jam;

                if ($request->paket_harga_id) {
                    $paket = PaketHargaBooking::where('id', $request->paket_harga_id)
                        ->where('jenis_unit_id', $unit->jenis_unit_id)
                        ->first();

                    if (!$paket) {
                        throw new \Exception('Paket harga tidak sesuai dengan jenis unit.');
                    }

                    if ((int) $paket->jumlah_jam !== (int) $jumlahJam) {
                        throw new \Exception('Jumlah jam tidak sesuai dengan paket harga.');
                    }

                    $harga = $paket->harga;
                }

                if ($request->paket_khusus_id) {
                    $paket = PaketKhususBooking::where('id', $request->paket_khusus_id)
                        ->where('jenis_unit_id', $unit->jenis_unit_id)
                        ->first();

                    if (!$paket) {
                        throw new \Exception('Paket khusus tidak sesuai dengan jenis unit.');
                    }

                    if (isset($paket->jumlah_jam)) {
                        $jumlahJam = $paket->jumlah_jam;
                    }

                    $harga = $paket->harga;
                }

                return Billing::create([
                    'billing_unit_id' => $unit->id,
                    'nama_customer'   => $request->nama_customer,
                    'jumlah_jam'      => $jumlahJam,
                    'paket_harga_id'  => $request->paket_harga_id,
                    'paket_khusus_id' => $request->paket_khusus_id,
                    'harga_awal'      => $harga,
                    'harga_final'     => $harga,
                    'metode_bayar'    => $request->metode_bayar,
                    'status_bayar'    => 'pending',
                    'status_sesi'     => 'available',
                    'catatan'         => $request->catatan,
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                ]);
            });

            if ($billing->metode_bayar === 'midtrans') {
                $snapToken = $this->createBillingSnapToken($billing);

                return response()->json([
                    'message' => 'Billing berhasil dibuat. Silakan lanjutkan pembayaran.',
                    'billing_id' => $billing->id,
                    'snap_token' => $snapToken,
                    'billing' => $billing->fresh(),
                ]);
            }

            return response()->json([
                'message' => 'Billing berhasil dibuat.',
                'billing' => $billing
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
    
    public function startSesi(Request $request, $id)
    {
        $billing = Billing::with('unit')->findOrFail($id);

        if ($billing->status_bayar !== 'paid') {
            return response()->json(['message' => 'Billing belum lunas.'], 422);
        }

        if ($billing->status_sesi !== 'available') {
            return response()->json(['message' => 'Sesi tidak bisa distart.'], 422);
        }

        if ($billing->pause_at !== null) {
            return response()->json(['message' => 'Billing sedang dalam kondisi pause.'], 422);
        }

        if (!$billing->unit || !$billing->unit->is_active) {
            return response()->json(['message' => 'Unit tidak aktif atau tidak ditemukan.'], 422);
        }

        $jamMulai   = now();
        $jamSelesai = $jamMulai->copy()->addHours($billing->jumlah_jam);

        $billing->update([
            'status_sesi' => 'active',
            'jam_mulai'   => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'updated_by'  => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Sesi berhasil dimulai.',
            'billing' => $billing
        ]);
    }

    public function pauseSesi(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        if ($billing->status_sesi !== 'active') {
            return response()->json(['message' => 'Sesi tidak sedang aktif.'], 422);
        }

        if ($billing->pause_at !== null) {
            return response()->json(['message' => 'Sesi sudah dalam kondisi pause.'], 422);
        }

        $billing->update([
            'pause_at'    => now(),
            'status_sesi' => 'available',
            'updated_by'  => Auth::id(),
        ]);

        return response()->json(['message' => 'Sesi berhasil di-pause.']);
    }

    public function resumeSesi(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        if ($billing->pause_at === null) {
            return response()->json(['message' => 'Sesi tidak dalam kondisi pause.'], 422);
        }

        if ($billing->status_sesi !== 'available') {
            return response()->json(['message' => 'Status sesi tidak valid untuk resume.'], 422);
        }

        if (!$billing->jam_selesai) {
            return response()->json(['message' => 'Jam selesai belum tersedia.'], 422);
        }

        $pauseDurasi = $billing->pause_at->diffInMinutes(now(), true);
        $totalPause  = ($billing->total_pause_menit ?? 0) + $pauseDurasi;

        $jamSelesaiBaru = $billing->jam_selesai->copy()->addMinutes($pauseDurasi);

        $billing->update([
            'status_sesi'       => 'active',
            'pause_at'          => null,
            'total_pause_menit' => $totalPause,
            'jam_selesai'       => $jamSelesaiBaru,
            'updated_by'        => Auth::id(),
        ]);

        return response()->json(['message' => 'Sesi berhasil di-resume.']);
    }

    public function completeSesi(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        if ($billing->status_sesi === 'completed') {
            return response()->json(['message' => 'Sesi sudah selesai.'], 422);
        }

        if (!in_array($billing->status_sesi, ['active', 'available'])) {
            return response()->json(['message' => 'Status sesi tidak valid untuk diselesaikan.'], 422);
        }

        if ($billing->status_bayar !== 'paid') {
            return response()->json(['message' => 'Billing belum lunas, tidak bisa diselesaikan.'], 422);
        }

        $catatanTambahan = '';

        if ($billing->pause_at !== null) {
            $pauseDurasi = $billing->pause_at->diffInMinutes(now(), true);

            $billing->total_pause_menit = ($billing->total_pause_menit ?? 0) + $pauseDurasi;
            $billing->pause_at = null;

            $catatanTambahan = "\nSesi diselesaikan saat kondisi pause.";
        }

        $billing->status_sesi = 'completed';
        $billing->catatan = trim(($billing->catatan ?? '') . $catatanTambahan);
        $billing->updated_by = Auth::id();
        $billing->save();

        return response()->json(['message' => 'Sesi berhasil diselesaikan.']);
    }

    public function markAsPaid($id)
    {
        $billing = Billing::findOrFail($id);

        if ($billing->status_bayar === 'paid') {
            return response()->json(['message' => 'Billing sudah lunas.'], 422);
        }

        if ($billing->status_sesi !== 'available') {
            return response()->json(['message' => 'Status sesi tidak valid untuk pembayaran.'], 422);
        }

        $billing->update([
            'status_bayar' => 'paid',
            'updated_by'   => Auth::id(),
        ]);

        return response()->json(['message' => 'Pembayaran berhasil ditandai lunas.']);
    }

    public function confirmMidtrans($id)
    {
        $billing = Billing::findOrFail($id);

        if ($billing->metode_bayar !== 'midtrans') {
            return response()->json([
                'message' => 'Billing ini bukan pembayaran Midtrans.'
            ], 422);
        }

        if ($billing->status_bayar === 'paid') {
            return response()->json([
                'message' => 'Billing sudah lunas.'
            ]);
        }

        if ($billing->status_sesi !== 'available') {
            return response()->json([
                'message' => 'Status sesi tidak valid untuk pembayaran.'
            ], 422);
        }

        $billing->update([
            'status_bayar' => 'paid',
            'updated_by'   => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Pembayaran Midtrans berhasil dikonfirmasi.'
        ]);
    }
    public function cancel($id)
    {
        $billing = Billing::findOrFail($id);

        if ($billing->status_sesi === 'completed') {
            return response()->json(['message' => 'Billing yang sudah selesai tidak bisa dibatalkan.'], 422);
        }

        if ($billing->status_sesi === 'active') {
            return response()->json(['message' => 'Sesi aktif tidak bisa dibatalkan langsung.'], 422);
        }

        $billing->update([
            'status_sesi' => 'completed',
            'catatan'     => trim(($billing->catatan ?? '') . "\nBilling dibatalkan."),
            'updated_by'  => Auth::id(),
        ]);

        return response()->json(['message' => 'Billing berhasil dibatalkan.']);
    }

    private function createExtendSnapToken(BillingExtend $extend)
    {
        $this->setupMidtrans();

        $extend->load('billing.unit');

        $orderId = 'BILLING-EXTEND-' . $extend->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $extend->harga_tambah,
            ],

            'customer_details' => [
                'first_name' => $extend->billing->nama_customer ?? 'Customer',
            ],

            'item_details' => [
                [
                    'id' => 'BILLING-EXTEND-' . $extend->id,
                    'price' => (int) $extend->harga_tambah,
                    'quantity' => 1,
                    'name' => 'Extend Billing - ' . ($extend->billing->unit->nama_unit ?? 'Unit'),
                ],
            ],

            'callbacks' => [
                'finish' => route('operational.billing.index'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $extend->update([
            'midtrans_order_id' => $orderId,
            'midtrans_token'    => $snapToken,
            'updated_by'        => Auth::id(),
        ]);

        return $snapToken;
    }

    public function extend(Request $request, $id)
    {
        $request->validate([
            'paket_harga_id' => 'required|exists:paket_harga_booking,id',
            'metode_bayar'   => 'required|in:cash,midtrans',
        ]);

        try {
            $extend = DB::transaction(function () use ($request, $id) {
                $billing = Billing::with('unit')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($billing->status_sesi !== 'active') {
                    throw new \Exception('Extend hanya bisa dilakukan saat sesi aktif.');
                }

                if ($billing->pause_at !== null) {
                    throw new \Exception('Sesi sedang pause, resume dulu sebelum extend.');
                }

                if (!$billing->jam_selesai) {
                    throw new \Exception('Jam selesai belum tersedia.');
                }

                if ($billing->status_bayar !== 'paid') {
                    throw new \Exception('Billing utama harus sudah lunas sebelum extend.');
                }

                $pendingExtend = BillingExtend::where('billing_id', $billing->id)
                    ->where('status_bayar', 'pending')
                    ->exists();

                if ($pendingExtend) {
                    throw new \Exception('Masih ada extend pending. Selesaikan pembayaran extend terlebih dahulu.');
                }

                $paket = PaketHargaBooking::where('id', $request->paket_harga_id)
                    ->where('jenis_unit_id', $billing->unit->jenis_unit_id)
                    ->first();

                if (!$paket) {
                    throw new \Exception('Paket harga extend tidak sesuai dengan jenis unit.');
                }

                $extend = BillingExtend::create([
                    'billing_id'        => $billing->id,
                    'jumlah_jam_tambah' => $paket->jumlah_jam,
                    'harga_tambah'      => $paket->harga,
                    'metode_bayar'      => $request->metode_bayar,
                    'status_bayar'      => $request->metode_bayar === 'cash' ? 'paid' : 'pending',
                    'created_by'        => Auth::id(),
                    'updated_by'        => Auth::id(),
                ]);

                if ($request->metode_bayar === 'cash') {
                    $billing->update([
                        'jumlah_jam'  => $billing->jumlah_jam + $paket->jumlah_jam,
                        'harga_final' => $billing->harga_final + $paket->harga,
                        'jam_selesai' => $billing->jam_selesai->copy()->addHours($paket->jumlah_jam),
                        'updated_by'  => Auth::id(),
                    ]);
                }

                return $extend;
            });

            if ($extend->metode_bayar === 'midtrans') {
                $snapToken = $this->createExtendSnapToken($extend);

                return response()->json([
                    'message' => 'Extend berhasil dibuat. Silakan lanjutkan pembayaran.',
                    'extend_id' => $extend->id,
                    'snap_token' => $snapToken,
                    'extend' => $extend->fresh(),
                ]);
            }

            return response()->json([
                'message' => 'Extend berhasil dibuat.',
                'extend'  => $extend,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function confirmExtendMidtrans($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $extend = BillingExtend::with('billing')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($extend->metode_bayar !== 'midtrans') {
                    throw new \Exception('Extend ini bukan pembayaran Midtrans.');
                }

                if ($extend->status_bayar === 'paid') {
                    return;
                }

                if (!$extend->billing) {
                    throw new \Exception('Billing utama tidak ditemukan.');
                }

                $billing = Billing::lockForUpdate()->findOrFail($extend->billing_id);

                if ($billing->status_sesi !== 'active') {
                    throw new \Exception('Extend hanya bisa dikonfirmasi saat sesi aktif.');
                }

                if ($billing->pause_at !== null) {
                    throw new \Exception('Sesi sedang pause, resume dulu sebelum konfirmasi extend.');
                }

                if (!$billing->jam_selesai) {
                    throw new \Exception('Jam selesai belum tersedia.');
                }

                $extend->update([
                    'status_bayar' => 'paid',
                    'updated_by'   => Auth::id(),
                ]);

                $billing->update([
                    'jumlah_jam'  => $billing->jumlah_jam + $extend->jumlah_jam_tambah,
                    'harga_final' => $billing->harga_final + $extend->harga_tambah,
                    'jam_selesai' => $billing->jam_selesai->copy()->addHours($extend->jumlah_jam_tambah),
                    'updated_by'  => Auth::id(),
                ]);
            });

            return response()->json([
                'message' => 'Pembayaran extend berhasil. Waktu billing sudah ditambahkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function pindahUnit(Request $request, $id)
    {
        $request->validate([
            'ke_unit_id' => 'required|exists:billing_units,id',
            'alasan'     => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $billing = Billing::lockForUpdate()->findOrFail($id);

                if (!in_array($billing->status_sesi, ['available', 'active'])) {
                    throw new \Exception('Billing tidak bisa dipindahkan.');
                }

                if ($billing->billing_unit_id == $request->ke_unit_id) {
                    throw new \Exception('Unit tujuan tidak boleh sama dengan unit asal.');
                }

                $unitTujuan = BillingUnit::where('id', $request->ke_unit_id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (!$unitTujuan) {
                    throw new \Exception('Unit tujuan tidak aktif atau tidak ditemukan.');
                }

                if ($unitTujuan->jenis_unit_id != $billing->unit->jenis_unit_id) {
                    throw new \Exception('Pindah unit hanya boleh ke jenis unit yang sama.');
                }

                $unitTujuanSibuk = Billing::where('billing_unit_id', $unitTujuan->id)
                    ->whereIn('status_sesi', ['available', 'active'])
                    ->lockForUpdate()
                    ->exists();

                if ($unitTujuanSibuk) {
                    throw new \Exception('Unit tujuan sedang digunakan atau di-hold.');
                }

                BillingPindahUnit::create([
                    'billing_id'    => $billing->id,
                    'dari_unit_id'  => $billing->billing_unit_id,
                    'ke_unit_id'    => $unitTujuan->id,
                    'alasan'        => $request->alasan,
                    'created_by'    => Auth::id(),
                    'updated_by'    => Auth::id(),
                ]);

                $billing->update([
                    'billing_unit_id' => $unitTujuan->id,
                    'updated_by'      => Auth::id(),
                ]);
            });

            return response()->json(['message' => 'Billing berhasil dipindahkan ke unit baru.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function refund(Request $request, $id)
    {
        $request->validate([
            'nominal_refund' => 'required|integer|min:1',
            'alasan'         => 'required|string|max:500',
        ]);

        try {
            $refund = DB::transaction(function () use ($request, $id) {
                $billing = Billing::lockForUpdate()->findOrFail($id);

                if ($billing->status_bayar !== 'paid') {
                    throw new \Exception('Refund hanya bisa dilakukan pada billing yang sudah lunas.');
                }

                if ($request->nominal_refund > $billing->harga_final) {
                    throw new \Exception('Nominal refund tidak boleh lebih besar dari harga final.');
                }

                $refund = BillingRefund::create([
                    'billing_id'      => $billing->id,
                    'nominal_refund'  => $request->nominal_refund,
                    'alasan'          => $request->alasan,
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                ]);

                $billing->update([
                    'harga_final' => $billing->harga_final - $request->nominal_refund,
                    'catatan'     => trim(($billing->catatan ?? '') . "\nRefund: Rp " . number_format($request->nominal_refund, 0, ',', '.') . " - " . $request->alasan),
                    'updated_by'  => Auth::id(),
                ]);

                return $refund;
            });

            return response()->json([
                'message' => 'Refund berhasil dicatat.',
                'refund'  => $refund,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function history(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $units = BillingUnit::with('jenisUnit')
            ->where('is_active', true)
            ->orderBy('lantai')
            ->orderBy('id')
            ->get();

        $query = Billing::with([
                'unit.jenisUnit',
                'paketHarga',
                'paketKhusus',
                'createdBy',
                'updatedBy',
                'extends',
                'refunds',
            ])
            ->where('status_sesi', 'completed')
            ->whereDate('updated_at', $tanggal);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $numericSearch = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $numericSearch) {
                $q->where('nama_customer', 'like', "%{$search}%")
                    ->orWhereHas('unit', function ($unitQuery) use ($search) {
                        $unitQuery->where('nama_unit', 'like', "%{$search}%");
                    })
                    ->orWhereHas('unit.jenisUnit', function ($jenisQuery) use ($search) {
                        $jenisQuery->where('tipe', 'like', "%{$search}%");
                    });

                if ($numericSearch !== '') {
                    $q->orWhere('id', (int) $numericSearch);
                }
            });
        }

        if ($request->filled('unit_id')) {
            $query->where('billing_unit_id', $request->unit_id);
        }

        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }

        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }

        $billings = $query
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $pendapatanHariIni = Billing::where('status_sesi', 'completed')
            ->where('status_bayar', 'paid')
            ->whereDate('updated_at', $tanggal)
            ->sum('harga_final');

        return view('operational.billing.history', compact(
            'billings',
            'units',
            'pendapatanHariIni',
            'tanggal'
        ));
    }

    public function updateHargaFinal(Request $request, $id)
    {
        $request->validate([
            'harga_final' => 'required|integer|min:0',
            'catatan'     => 'required|string|max:500',
        ]);

        $billing = Billing::findOrFail($id);

        if ($request->harga_final > $billing->harga_awal) {
            return response()->json([
                'message' => 'Harga final tidak boleh lebih besar dari harga awal.'
            ], 422);
        }

        $billing->update([
            'harga_final' => $request->harga_final,
            'catatan'     => trim(($billing->catatan ?? '') . "\nPerubahan harga: " . $request->catatan),
            'updated_by'  => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Harga final berhasil diperbarui.'
        ]);
    }

    // private function autoCompleteExpiredSessions()
    // {
    //     Billing::where('status_sesi', 'active')
    //         ->whereNotNull('jam_selesai')
    //         ->where('jam_selesai', '<=', now())
    //         ->update([
    //             'status_sesi' => 'completed',
    //             'updated_at'  => now(),
    //         ]);
    // }

    public function getAvailableUnits(Billing $billing)
    {
        $billing->load('unit');

        $units = BillingUnit::with('jenisUnit')
            ->where('is_active', true)
            ->where('id', '!=', $billing->billing_unit_id)
            ->where('jenis_unit_id', $billing->unit->jenis_unit_id)
            ->whereDoesntHave('activeBilling')
            ->get();

        return response()->json($units->map(function ($unit) {
            return [
                'id' => $unit->id,
                'nama_unit' => $unit->nama_unit,
                'jenis_tipe' => $unit->jenisUnit->tipe ?? '-',
            ];
        }));
    }

    public function show($id)
    {
        $billing = Billing::with([
            'unit.jenisUnit',
            'paketHarga',
            'paketKhusus',
            'extends.createdBy',
            'refunds.createdBy',
            'pindahUnit.dariUnit.jenisUnit',
            'pindahUnit.keUnit.jenisUnit',
            'pindahUnit.createdBy',
            'createdBy',
            'updatedBy',
        ])->findOrFail($id);

        return view('operational.billing.show', compact('billing'));
    }

}