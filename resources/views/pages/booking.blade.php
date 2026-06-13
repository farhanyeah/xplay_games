@extends('layouts.main')

@section('title', 'Booking PlayStation | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/booking.css') }}">
@endpush

@section('content')

<!-- Hero Section -->
<section class="booking-hero">
    <div class="container booking-hero-wrapper">
        <div class="booking-hero-content reveal">
            <p class="booking-subtitle">LAYANAN BOOKING XPLAY</p>
            <h1>Booking PlayStation Lebih Mudah dan Praktis</h1>
            <p class="booking-description">
                Cek ketersediaan slot bermain secara real-time,
                lakukan booking online, dan nikmati pengalaman gaming terbaik
                bersama XPLAY Games
            </p>
        </div>
    </div>
</section>

<!-- Tabel Antrian -->
@guest
<section class="antrian-section" id="antrian">
    <div class="container">

        <div class="section-heading reveal">
            <h2>Antrian Booking</h2>
            <p>Daftar booking PlayStation yang sedang aktif</p>
        </div>

        <!-- Filter Toggle -->
        <div class="filter-toggle reveal">
            <button type="button" class="filter-btn active" id="filterHariIni">Hari Ini</button>
            <button type="button" class="filter-btn" id="filterSemua">Semua</button>
        </div>

        <div class="table-container reveal">
            <table class="xplay-table" id="tabelAntrian">
                <thead>
                    <tr>
                        <th>Unit</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antrianBooking as $antrian)
                    <tr class="antrian-row"
                        data-tanggal="{{ \Carbon\Carbon::parse($antrian->tanggal)->format('Y-m-d') }}">
                        <td>{{ $antrian->unit->kode_unit }} — {{ $antrian->unit->jenisUnit->tipe }}</td>
                        <td>{{ \Carbon\Carbon::parse($antrian->tanggal)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($antrian->jam_mulai)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($antrian->jam_selesai)->format('H:i') }}</td>
                        <td>
                            @switch($antrian->status_booking)
                            @case('booked')
                            <span class="badge rented">Booked</span>
                            @break
                            @case('done')
                            <span class="badge completed">Selesai</span>
                            @break
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada antrian hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Antrian -->
        @if($totalHalamanAntrian > 1)
        <div class="custom-pagination">
            <button type="button" class="page-btn" onclick="ubahHalamanAntrian({{ $halamanAntrian - 1 }})"
                {{ $halamanAntrian == 1 ? 'disabled' : '' }}>
                &laquo; Prev
            </button>

            @for($i = 1; $i <= $totalHalamanAntrian; $i++) @if($i==1 || $i==$totalHalamanAntrian || ($i>=
                $halamanAntrian - 2 && $i <= $halamanAntrian + 2)) <button type="button"
                    class="page-btn {{ $i == $halamanAntrian ? 'active' : '' }}" onclick="ubahHalamanAntrian({{ $i }})">
                    {{ $i }}
                    </button>
                    @elseif($i == $halamanAntrian - 3 || $i == $halamanAntrian + 3)
                    <span class="page-ellipsis">...</span>
                    @endif
                    @endfor

                    <button type="button" class="page-btn" onclick="ubahHalamanAntrian({{ $halamanAntrian + 1 }})"
                        {{ $halamanAntrian == $totalHalamanAntrian ? 'disabled' : '' }}>
                        Next &raquo;
                    </button>
        </div>

        <div style="text-align:center;margin-top:10px;font-size:14px;color:#64748b;">
            Menampilkan {{ $antrianBooking->count() }} dari {{ $totalAntrian }} antrian
        </div>
        @endif

        <div class="guest-login-box reveal">
            <h3>Masuk untuk melakukan booking PlayStation</h3>
            <a href="{{ route('login') }}" class="guest-login-button">
                Masuk Sekarang
            </a>
        </div>
        
    </div>
</section>
@endguest

<!-- Form Booking -->
@auth
@if(in_array(auth()->user()->role, ['customer', 'staf', 'owner']))
<section class="booking-form-section">
    <div class="container">

        <div class="section-heading reveal">
            <h2>Form Booking PlayStation</h2>
            <p>Lengkapi data berikut untuk melakukan booking</p>
        </div>

        <div class="booking-form-card reveal">
            <form method="POST" action="{{ route('booking.store') }}" id="bookingForm" novalidate>
                @csrf

                <div class="form-grid">

                    <!-- Nama -->
                    <div class="form-group">
                        <label>Nama <span class="required">*</span></label>
                        @if(auth()->user()->role === 'customer')
                        <input type="text" name="nama" value="{{ Auth::user()->name }}" readonly
                            style="background: rgba(255,255,255,0.04);">
                        @else
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            placeholder="Masukkan nama penyewa" required>
                        @endif
                        @error('nama')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div class="form-group">
                        <label>No HP <span class="required">*</span></label>
                        <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                            placeholder="Contoh: 081234567890" required>
                        <span class="error-message" data-input="no_hp"></span>
                        @error('no_hp')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div class="form-group">
                        <label>Unit PlayStation <span class="required">*</span></label>
                        <select name="unit_id" id="unitSelect" required>
                            <option value="">Pilih Unit</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}"
                                data-jenis="{{ $unit->jenis_unit_id }}"
                                data-tipe="{{ $unit->jenisUnit->tipe }}">
                                {{ $unit->kode_unit }} — {{ $unit->jenisUnit->tipe }}
                            </option>
                            @endforeach
                        </select>
                        <span class="error-message" data-input="unit_id"></span>
                        @error('unit_id')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label>Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="tanggalInput" required>
                        <span class="error-message" data-input="tanggal"></span>
                        @error('tanggal')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tipe Booking -->
                    <div class="form-group full-width">
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            <label style="margin: 0;">Tipe Booking <span class="required">*</span></label>
                            <div class="tooltip-wrapper">
                                <i class="fas fa-circle-info tooltip-icon"></i>
                                <div class="tooltip-box">
                                    Rentang waktu menunjukkan batas jam mulai yang tersedia untuk setiap paket. Di luar jam tersebut, paket tidak dapat dipilih.
                                </div>
                            </div>
                        </div>
                        <select name="tipe_booking" id="tipeBookingSelect" required>
                            <option value="">Pilih Tipe Booking</option>
                            <option value="per_jam">Per Jam</option>
                            <option value="happy_hour">Happy Hour - Paket 3 Jam (10:00 - 16:00)</option>
                            <option value="paket_pagi">Paket Pagi - Paket 6 Jam (10:00 - 12:00)</option>
                            <option value="paket_malam">Paket Malam - Paket 5 Jam (18:00 - 22:00)</option>
                        </select>
                        <span class="error-message" data-input="tipe_booking"></span>
                        @error('tipe_booking')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jumlah Jam (muncul kalau Per Jam) -->
                    <div class="form-group" id="jumlahJamGroup" style="display: none;">
                        <label>Jumlah Jam <span class="required">*</span></label>
                        <select name="paket_id" id="paketSelect">
                            <option value="">Pilih Jumlah Jam</option>
                            @foreach($paketHarga as $paket)
                            <option value="{{ $paket->id }}"
                                data-jenis="{{ $paket->jenis_unit_id }}"
                                data-jam="{{ $paket->jumlah_jam }}"
                                data-harga="{{ $paket->harga }}">
                                {{ $paket->jumlah_jam }} Jam —
                                Rp {{ number_format($paket->harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                        <span class="error-message" data-input="paket_id"></span>
                        @error('paket_id')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Paket Khusus Hidden (auto-set by JS) -->
                    <input type="hidden" name="paket_khusus_id" id="paketKhususId">

                    <!-- Jam Mulai -->
                    <div class="form-group">
                        <label>Jam Mulai <span class="required">*</span></label>
                        <input type="time" name="jam_mulai" id="jamMulaiInput" required>
                        <span class="error-message" data-input="jam_mulai"></span>
                        @error('jam_mulai')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jam Selesai -->
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jamSelesaiInput" readonly
                            style="background: rgba(255,255,255,0.04);">
                        <small class="from-hint">Dihitung otomatis dari jam mulai + jumlah jam</small>
                    </div>

                    <!-- Total Harga -->
                    <div class="form-group">
                        <label>Total Harga</label>
                        <div class="harga-display" id="hargaDisplay">Rp ...</div>
                        <input type="hidden" name="harga" id="hargaInput">
                    </div>

                </div>

                <button type="button" class="submit-button" id="openConfirmBooking">
                    Ajukan Booking
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Modal Konfirmasi Booking -->
<div class="confirm-modal" id="confirmBookingModal">
    <div class="confirm-modal-content">
        <h2>Konfirmasi Booking</h2>
        <p>Pastikan data booking sudah benar sebelum melanjutkan</p>

        <div class="confirm-summary">
            <div class="summary-item">
                <span>Unit</span>
                <strong id="confirmUnit">-</strong>
            </div>
            <div class="summary-item">
                <span>Tanggal</span>
                <strong id="confirmTanggal">-</strong>
            </div>
            <div class="summary-item">
                <span>Tipe Booking</span>
                <strong id="confirmTipe">-</strong>
            </div>
            <div class="summary-item">
                <span>Jam Mulai</span>
                <strong id="confirmJamMulai">-</strong>
            </div>
            <div class="summary-item">
                <span>Jam Selesai</span>
                <strong id="confirmJamSelesai">-</strong>
            </div>
            <div class="summary-item">
                <span>Total Harga</span>
                <strong id="confirmHarga">-</strong>
            </div>
        </div>

        <div class="confirm-actions">
            <button type="button" class="cancel-button" id="cancelBookingModal">Batal</button>
            <button type="button" class="confirm-button" id="confirmBookingSubmit">
                Konfirmasi Booking
            </button>
        </div>
    </div>
</div>

@endif
@endauth

<!-- Riwayat Booking Customer -->
@auth
@if(in_array(auth()->user()->role, ['customer', 'staf', 'owner']))
<section class="booking-history-section" id="booking-history">
    <div class="container">

        <div class="section-heading reveal">
            @if(auth()->user()->role !== 'customer')
            <h2>Data Booking Pelanggan</h2>
            <p>Riwayat booking yang Anda buat untuk pelanggan</p>
            @else
            <h2>Data Booking Saya</h2>
            <p>Riwayat booking PlayStation milik Anda</p>
            @endif
        </div>

        <div class="table-container reveal">
            <table class="xplay-table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Unit</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Status</th>
                        @if(auth()->user()->role === 'customer')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookingSaya as $booking)
                    <tr>
                        <td>
                            <a href="#" class="transaction-link-booking"
                                data-code="{{ $booking->transaction_code }}"
                                data-unit="{{ $booking->unit->kode_unit }} — {{ $booking->unit->jenisUnit->tipe }}"
                                data-nama="{{ $booking->nama }}"
                                data-no-hp="{{ $booking->no_hp }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}"
                                data-jam-mulai="{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}"
                                data-jam-selesai="{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}"
                                data-jumlah-jam="{{ $booking->jumlah_jam }} Jam"
                                data-tipe="{{ $booking->paket_id ? $booking->paket->jumlah_jam . ' Jam (Per Jam)' : $booking->paketKhusus->nama_paket }}"
                                data-harga="Rp {{ number_format($booking->harga, 0, ',', '.') }}"
                                data-payment-status="{{ $booking->payment_status }}"
                                data-status="{{ $booking->status_booking }}">
                                {{ $booking->transaction_code }}
                            </a>
                        </td>
                        <td>{{ $booking->unit->kode_unit }} — {{ $booking->unit->jenisUnit->tipe }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                        <td>
                            @switch($booking->status_booking)
                            @case('pending')
                            <span class="badge pending">Diproses</span>
                            @break
                            @case('booked')
                            <span class="badge rented">Booked</span>
                            @break
                            @case('done')
                            <span class="badge completed">Selesai</span>
                            @break
                            @case('cancelled')
                            <span class="badge cancelled">Dibatalkan</span>
                            @break
                            @endswitch
                        </td>

                        @if(auth()->user()->role === 'customer')
                        <td>
                            @if($booking->status_booking === 'pending' && $booking->payment_status === 'unpaid')
                            <button type="button" class="btn-icon btn-bayar-booking"
                                data-booking-id="{{ $booking->id }}"
                                data-snap-token="{{ $booking->midtrans_token }}">
                                <i class="fas fa-credit-card"></i>
                            </button>
                            @else
                            <span style="color: #94a3b8; font-size: 12px;">—</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'customer' ? 7 : 6 }}" class="text-center">
                            Belum ada data booking
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Booking Saya -->
            @if($totalHalaman > 1)
            <div class="custom-pagination">
                <button type="button" class="page-btn" onclick="ubahHalaman({{ $halaman - 1 }})"
                    {{ $halaman == 1 ? 'disabled' : '' }}>
                    &laquo; Prev
                </button>

                @for($i = 1; $i <= $totalHalaman; $i++) @if($i==1 || $i==$totalHalaman || ($i>= $halaman - 2 && $i <=
                        $halaman + 2)) <button type="button" class="page-btn {{ $i == $halaman ? 'active' : '' }}"
                        onclick="ubahHalaman({{ $i }})">
                        {{ $i }}
                        </button>
                        @elseif($i == $halaman - 3 || $i == $halaman + 3)
                        <span class="page-ellipsis">...</span>
                        @endif
                        @endfor

                        <button type="button" class="page-btn" onclick="ubahHalaman({{ $halaman + 1 }})"
                            {{ $halaman == $totalHalaman ? 'disabled' : '' }}>
                            Next &raquo;
                        </button>
            </div>

            <div style="text-align:center;margin-top:10px;font-size:14px;color:#64748b;">
                Menampilkan {{ $bookingSaya->count() }} dari {{ $totalBooking }} booking
            </div>
            @endif
        </div>

    </div>
</section>
@endif
@endauth

<!-- Modal Detail Booking -->
<div class="modal-overlay" id="detailBookingModal">
    <div class="modal-content">

        <button class="modal-close" id="closeDetailBookingModal">&times;</button>

        <h2 class="modal-title">Detail Booking</h2>
        <p class="modal-subtitle">Informasi lengkap transaksi booking Anda</p>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Kode Transaksi</span>
                <strong class="detail-value" id="detailBookingCode">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Unit</span>
                <strong class="detail-value" id="detailBookingUnit">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nama</span>
                <strong class="detail-value" id="detailBookingNama">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">No HP</span>
                <strong class="detail-value" id="detailBookingHp">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal</span>
                <strong class="detail-value" id="detailBookingTanggal">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tipe Booking</span>
                <strong class="detail-value" id="detailBookingTipe">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Jam Mulai</span>
                <strong class="detail-value" id="detailBookingJamMulai">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Jam Selesai</span>
                <strong class="detail-value" id="detailBookingJamSelesai">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Jumlah Jam</span>
                <strong class="detail-value" id="detailBookingJumlahJam">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total Harga</span>
                <strong class="detail-value" id="detailBookingHarga">-</strong>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status Pembayaran</span>
                <strong class="detail-value" id="detailBookingPaymentStatus">-</strong>
            </div>
            <div class="detail-item full-width">
                <span class="detail-label">Status Booking</span>
                <strong class="detail-value" id="detailBookingStatus">-</strong>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" id="closeDetailBookingModalBtn">Tutup</button>
        </div>

    </div>
</div>

@php
$paketHargaMap = [];
foreach($paketHarga->groupBy('jenis_unit_id') as $jenisId => $pakets) {
    foreach($pakets as $p) {
        $paketHargaMap[$jenisId][$p->id] = [
            'jam' => $p->jumlah_jam,
            'harga' => $p->harga,
        ];
    }
}

$paketKhususMap = [];
foreach($paketKhusus->groupBy('jenis_unit_id') as $jenisId => $pakets) {
    foreach($pakets as $p) {
        $paketKhususMap[$jenisId][] = [
            'id' => $p->id,
            'nama' => $p->nama_paket,
            'jam' => $p->jumlah_jam,
            'harga' => $p->harga,
            'hari_berlaku' => $p->hari_berlaku,
            'jam_mulai_berlaku' => $p->jam_mulai_berlaku,
            'jam_selesai_berlaku' => $p->jam_selesai_berlaku,
        ];
    }
}
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const todayStr = "{{ now()->toDateString() }}";
    const tanggalInput = document.getElementById('tanggalInput');
    if (tanggalInput) tanggalInput.min = todayStr;

    let paketHargaMap = @json($paketHargaMap);
    let paketKhususMap = @json($paketKhususMap);

    const unitSelect = document.getElementById('unitSelect');
    const tipeBookingSelect = document.getElementById('tipeBookingSelect');
    const jumlahJamGroup = document.getElementById('jumlahJamGroup');
    const paketSelect = document.getElementById('paketSelect');
    const paketKhususIdInput = document.getElementById('paketKhususId');
    const jamMulaiInput = document.getElementById('jamMulaiInput');
    const jamSelesaiInput = document.getElementById('jamSelesaiInput');
    const hargaDisplay = document.getElementById('hargaDisplay');
    const hargaInput = document.getElementById('hargaInput');
    const bookingForm = document.getElementById('bookingForm');
    const confirmBookingModal = document.getElementById('confirmBookingModal');
    const cancelBookingModalBtn = document.getElementById('cancelBookingModal');

    // ===== FUNGSI CLOSE MODAL =====
    function closeConfirmModal() {
        confirmBookingModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // ===== FILTER ANTRIAN =====
    const filterHariIni = document.getElementById('filterHariIni');
    const filterSemua = document.getElementById('filterSemua');
    // const todayStr = "{{ now()->format('Y-m-d') }}";

    function applyFilter(mode) {
        document.querySelectorAll('.antrian-row').forEach(row => {
            if (mode === 'hari_ini') {
                row.style.display = row.dataset.tanggal === todayStr ? '' : 'none';
            } else {
                row.style.display = '';
            }
        });
    }

    // Default filter hari ini
    applyFilter('hari_ini');

    if (filterHariIni) {
        filterHariIni.addEventListener('click', function() {
            filterHariIni.classList.add('active');
            filterSemua.classList.remove('active');
            applyFilter('hari_ini');
        });
    }

    if (filterSemua) {
        filterSemua.addEventListener('click', function() {
            filterSemua.classList.add('active');
            filterHariIni.classList.remove('active');
            applyFilter('semua');
        });
    }

    // ===== FILTER PAKET BY UNIT =====
    if (unitSelect) {
        unitSelect.addEventListener('change', function() {
            const jenisUnitId = this.options[this.selectedIndex].getAttribute('data-jenis');

            // Reset tipe booking dan harga
            if (tipeBookingSelect) tipeBookingSelect.value = '';
            if (jumlahJamGroup) jumlahJamGroup.style.display = 'none';
            if (paketSelect) paketSelect.value = '';
            if (paketKhususIdInput) paketKhususIdInput.value = '';
            if (jamSelesaiInput) jamSelesaiInput.value = '';
            hargaDisplay.innerText = 'Rp ...';
            hargaInput.value = '';

            // Filter paket per jam sesuai jenis unit
            if (paketSelect) {
                Array.from(paketSelect.options).forEach(opt => {
                    if (opt.value === '') return;
                    opt.style.display = opt.getAttribute('data-jenis') === jenisUnitId ? '' : 'none';
                    opt.disabled = opt.getAttribute('data-jenis') !== jenisUnitId;
                });
            }
        });
    }

    // ===== TIPE BOOKING CHANGE =====
if (tipeBookingSelect) {
    tipeBookingSelect.addEventListener('change', function() {
        const tipe = this.value;
        const jenisUnitId = unitSelect
            ? unitSelect.options[unitSelect.selectedIndex].getAttribute('data-jenis')
            : null;

        // Reset semua dulu
        jumlahJamGroup.style.display = 'none';
        paketSelect.value = '';
        paketKhususIdInput.value = '';
        jamSelesaiInput.value = '';
        hargaDisplay.innerText = 'Rp ...';
        hargaInput.value = '';

        if (tipe === 'per_jam') {
            // Tampilkan dropdown jumlah jam
            jumlahJamGroup.style.display = 'block';

            // Enable/disable opsi paket sesuai jenis unit yang dipilih
            Array.from(paketSelect.options).forEach(opt => {
                if (opt.value === '') return;
                const cocok = opt.getAttribute('data-jenis') === jenisUnitId;
                opt.style.display = cocok ? '' : 'none';
                opt.disabled = !cocok;
            });

        } else {
            // Validasi Happy Hour hanya Sen–Jum
            if (tipe === 'happy_hour') {
                const tanggal = tanggalInput ? tanggalInput.value : '';
                if (tanggal) {
                    const hari = new Date(tanggal).getDay(); // 0=Minggu, 6=Sabtu
                    if (hari === 0 || hari === 6) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Tersedia',
                            text: 'Happy Hour hanya berlaku Senin–Jumat.',
                            confirmButtonColor: '#6366f1',
                        });
                        tipeBookingSelect.value = '';
                        return;
                    }
                }
            }

            // Cari dan set paket khusus yang sesuai
            if (jenisUnitId && paketKhususMap[jenisUnitId]) {
                const namaMap = {
                    'happy_hour': 'Happy Hour',
                    'paket_pagi': 'Paket Pagi',
                    'paket_malam': 'Paket Malam',
                };
                const namaCari = namaMap[tipe];
                const paket = paketKhususMap[jenisUnitId].find(p => p.nama === namaCari);

                if (paket) {
                    paketKhususIdInput.value = paket.id;
                    hargaDisplay.innerText = 'Rp ' + paket.harga.toLocaleString('id-ID');
                    hargaInput.value = paket.harga;
                    calculateJamSelesai(paket.jam);
                }
            }
        }
    });
}

    // ===== PAKET PER JAM CHANGE =====
    if (paketSelect) {
        paketSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const jam = parseInt(selected.getAttribute('data-jam'));
            const harga = parseInt(selected.getAttribute('data-harga'));

            if (jam && harga) {
                hargaDisplay.innerText = 'Rp ' + harga.toLocaleString('id-ID');
                hargaInput.value = harga;
                calculateJamSelesai(jam);
            }
        });
    }

    // ===== JAM MULAI CHANGE =====
    if (jamMulaiInput) {
        jamMulaiInput.addEventListener('change', function() {
            const tipe = tipeBookingSelect ? tipeBookingSelect.value : '';
            if (tipe === 'per_jam') {
                const selected = paketSelect.options[paketSelect.selectedIndex];
                const jam = parseInt(selected.getAttribute('data-jam'));
                if (jam) calculateJamSelesai(jam);
            } else if (tipe && paketKhususIdInput.value) {
                const jenisUnitId = unitSelect.options[unitSelect.selectedIndex].getAttribute('data-jenis');
                const paket = paketKhususMap[jenisUnitId]?.find(p => p.id == paketKhususIdInput.value);
                if (paket) calculateJamSelesai(paket.jam);
            }
        });
    }

    // ===== HITUNG JAM SELESAI =====
    function calculateJamSelesai(jumlahJam) {
        if (!jamMulaiInput.value) return;
        const [h, m] = jamMulaiInput.value.split(':').map(Number);
        const totalMenit = h * 60 + m + jumlahJam * 60;
        const jamSelesai = Math.floor(totalMenit / 60) % 24;
        const menitSelesai = totalMenit % 60;
        jamSelesaiInput.value = String(jamSelesai).padStart(2, '0') + ':' + String(menitSelesai).padStart(2, '0');
    }

    // ===== BUKA MODAL KONFIRMASI =====
    const openConfirmBtn = document.getElementById('openConfirmBooking');
    if (openConfirmBtn) {
        openConfirmBtn.addEventListener('click', function() {
            if (!bookingForm.checkValidity()) {
                bookingForm.reportValidity();
                return;
            }

            if (!hargaInput.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Lengkapi semua field terlebih dahulu.',
                    confirmButtonColor: '#6366f1',
                });
                return;
            }

            const unitText = unitSelect.options[unitSelect.selectedIndex].text;
            const tipeText = tipeBookingSelect.options[tipeBookingSelect.selectedIndex].text;
            const tanggal = new Date(tanggalInput.value).toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });

            document.getElementById('confirmUnit').innerText = unitText;
            document.getElementById('confirmTanggal').innerText = tanggal;
            document.getElementById('confirmTipe').innerText = tipeText;
            document.getElementById('confirmJamMulai').innerText = jamMulaiInput.value;
            document.getElementById('confirmJamSelesai').innerText = jamSelesaiInput.value;
            document.getElementById('confirmHarga').innerText = hargaDisplay.innerText;
            confirmBookingModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

        // ===== BATAL MODAL =====
        if (cancelBookingModalBtn) {
            cancelBookingModalBtn.addEventListener('click', function() {
                confirmBookingModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }

        // ===== KLIK LUAR MODAL =====
        confirmBookingModal.addEventListener('click', function(e) {
            if (e.target === confirmBookingModal) {
                confirmBookingModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // ===== ESCAPE KEY =====
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && confirmBookingModal.classList.contains('active')) {
                confirmBookingModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });


    // ===== KONFIRMASI BOOKING (MIDTRANS) =====
    const confirmBookingSubmit = document.getElementById('confirmBookingSubmit');
    if (confirmBookingSubmit) {
        confirmBookingSubmit.addEventListener('click', function() {
            confirmBookingModal.classList.remove('active');
            document.body.style.overflow = 'auto';
            const formData = new FormData(bookingForm);

            fetch("{{ route('booking.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.error,
                        confirmButtonColor: '#6366f1',
                    });
                    return;
                }

                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: firstError,
                        confirmButtonColor: '#6366f1',
                    });
                    return;
                }
                
                // Staf/owner — tidak ada snap_token, langsung sukses
                if (!data.snap_token) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Berhasil!',
                        text: data.message || 'Booking berhasil dibuat.',
                        confirmButtonColor: '#6366f1',
                    }).then(() => window.location.reload());
                    return;
                }

                window.snap.pay(data.snap_token, {

                    onSuccess: function() {
                        fetch(`/booking/${data.booking_id}/konfirmasi-midtrans`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Booking Berhasil!',
                                text: 'Pembayaran berhasil. Booking kamu sudah terkonfirmasi.',
                                confirmButtonColor: '#6366f1',
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pembayaran Berhasil',
                                text: 'Pembayaran berhasil, tetapi konfirmasi server gagal. Muat ulang halaman dan coba lagi.',
                                confirmButtonColor: '#6366f1',
                            }).then(() => window.location.reload());
                        });
                    },
                    onPending: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Pending',
                            text: 'Transaksi kamu masih pending. Klik tombol bayar di tabel untuk melanjutkan.',
                            confirmButtonColor: '#6366f1',
                        }).then(() => window.location.reload());
                    },
                    onError: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan saat proses pembayaran.',
                            confirmButtonColor: '#6366f1',
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    onClose: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Dibatalkan',
                            text: 'Booking disimpan sebagai pending. Silakan klik tombol bayar di tabel untuk mencoba lagi.',
                            confirmButtonColor: '#6366f1',
                        }).then(() => window.location.reload());
                    }
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: err.message,
                    confirmButtonColor: '#6366f1',
                });
            });
        });
    }

    // ===== BAYAR BOOKING PENDING =====
    document.querySelectorAll('.btn-bayar-booking').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.dataset.bookingId;
            const snapToken = this.dataset.snapToken;

            window.snap.pay(snapToken, {
                onSuccess: function() {
                    fetch(`/booking/${bookingId}/konfirmasi-midtrans`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Booking Berhasil!',
                            text: 'Pembayaran berhasil. Booking kamu sudah terkonfirmasi.',
                            confirmButtonColor: '#6366f1',
                        }).then(() => window.location.reload());
                    });
                },
                onPending: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran masih pending. Silakan coba lagi.',
                        confirmButtonColor: '#6366f1',
                    }).then(() => window.location.reload());
                },
                onError: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                        confirmButtonColor: '#6366f1',
                    }).then(() => window.location.reload());
                },
                onClose: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Ditutup',
                        text: 'Silakan klik tombol bayar untuk mencoba lagi.',
                        confirmButtonColor: '#6366f1',
                    }).then(() => window.location.reload());
                }
            });
        });
    });

    // ===== MODAL DETAIL BOOKING =====
    const detailBookingModal = document.getElementById('detailBookingModal');
    const closeDetailBtn = document.getElementById('closeDetailBookingModal');
    const closeDetailBtnText = document.getElementById('closeDetailBookingModalBtn');

    function openDetailModal() {
        detailBookingModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        detailBookingModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    document.querySelectorAll('.transaction-link-booking').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            document.getElementById('detailBookingCode').textContent = this.dataset.code || '-';
            document.getElementById('detailBookingUnit').textContent = this.dataset.unit || '-';
            document.getElementById('detailBookingNama').textContent = this.dataset.nama || '-';
            document.getElementById('detailBookingHp').textContent = this.getAttribute('data-no-hp') || '-';
            document.getElementById('detailBookingTanggal').textContent = this.dataset.tanggal || '-';
            document.getElementById('detailBookingTipe').textContent = this.dataset.tipe || '-';
            document.getElementById('detailBookingJamMulai').textContent = this.dataset.jamMulai || '-';
            document.getElementById('detailBookingJamSelesai').textContent = this.dataset.jamSelesai || '-';
            document.getElementById('detailBookingJumlahJam').textContent = this.dataset.jumlahJam || '-';
            document.getElementById('detailBookingHarga').textContent = this.dataset.harga || '-';

            const paymentStatus = this.dataset.paymentStatus;
            document.getElementById('detailBookingPaymentStatus').textContent =
                paymentStatus === 'paid' ? 'Lunas' : 'Belum Lunas';

            const statusMap = {
                'pending': 'Pending',
                'booked': 'Booked',
                'done': 'Selesai',
                'cancelled': 'Dibatalkan',
            };
            document.getElementById('detailBookingStatus').textContent =
                statusMap[this.dataset.status] || this.dataset.status;

            openDetailModal();
        });
    });

    if (closeDetailBtn) closeDetailBtn.addEventListener('click', closeDetailModal);
    if (closeDetailBtnText) closeDetailBtnText.addEventListener('click', closeDetailModal);

    if (detailBookingModal) {
        detailBookingModal.addEventListener('click', function(e) {
            if (e.target === detailBookingModal) closeDetailModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && detailBookingModal.classList.contains('active')) {
            closeDetailModal();
        }
    });

});
</script>

<script>
function ubahHalaman(halaman) {
    if(halaman < 1) return;
    const url = new URL(window.location.href);
    url.searchParams.set('halaman', halaman);
    window.location.href = url.toString();
}

function ubahHalamanAntrian(halaman) {
    if(halaman < 1) return;
    const url = new URL(window.location.href);
    url.searchParams.set('halaman_antrian', halaman);
    window.location.href = url.toString();
}
</script>
@endpush

@endsection