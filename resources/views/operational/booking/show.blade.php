@extends('layouts.operational')

@section('title', 'Detail Booking | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/data-booking.css') }}">
@endpush

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Detail Booking</h1>
    <a href="{{ route('operational.booking.index') }}" class="ops-btn-back">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<!-- Alert -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">

    <!-- Kolom Kiri — Info Transaksi -->
    <div class="col-lg-8">

        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-calendar-check mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">
                    Informasi Transaksi — {{ $booking->transaction_code }}
                </h6>
            </div>
            <div class="card-body ops-show-card-body">
                <div class="row">

                    <!-- Info Customer -->
                    <div class="col-md-6">
                        <div class="ops-info-section-title">Data Customer</div>
                        <table class="ops-info-table">
                            <tr>
                                <td class="ops-info-label">Nama</td>
                                <td class="ops-info-value">{{ $booking->nama }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">No HP</td>
                                <td class="ops-info-value">{{ $booking->no_hp }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Pembayaran</td>
                                <td class="ops-info-value">{{ ucfirst($booking->pembayaran) }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Status Bayar</td>
                                <td class="ops-info-value">
                                    @if($booking->payment_status === 'paid')
                                    <span class="ops-status-badge status-done">Lunas</span>
                                    @else
                                    <span class="ops-status-badge status-cancelled">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Info Booking -->
                    <div class="col-md-6">
                        <div class="ops-info-section-title">Data Booking</div>
                        <table class="ops-info-table">
                            <tr>
                                <td class="ops-info-label">Unit</td>
                                <td class="ops-info-value">
                                    {{ $booking->unit->kode_unit }}
                                    <span class="ops-info-sub">— {{ $booking->unit->jenisUnit->tipe }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Tanggal</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Jam Mulai</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Jam Selesai</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Jumlah Jam</td>
                                <td class="ops-info-value">{{ $booking->jumlah_jam }} Jam</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Tipe</td>
                                <td class="ops-info-value">
                                    @if($booking->paket_id)
                                    <span class="booking-tipe-badge tipe-perjam">Per Jam</span>
                                    @else
                                    <span
                                        class="booking-tipe-badge tipe-khusus">{{ $booking->paketKhusus->nama_paket }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Dibuat</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Breakdown Harga -->
                <div class="ops-harga-breakdown ops-harga-breakdown-2 mt-4">
                    <div class="ops-harga-item">
                        <div class="ops-harga-item-label">Jumlah Jam</div>
                        <div class="ops-harga-item-value">{{ $booking->jumlah_jam }} Jam</div>
                    </div>
                    <div class="ops-harga-item ops-harga-item-total">
                        <div class="ops-harga-item-label">Total Harga</div>
                        <div class="ops-harga-item-value-total">
                            Rp {{ number_format($booking->harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Kolom Kanan — Status & Aksi -->
    <div class="col-lg-4">

        <!-- Status Badge -->
        <div class="card ops-card mb-4">
            <div class="card-body ops-show-card-body text-center py-4">
                @switch($booking->status_booking)
                @case('pending')
                <div class="status-icon status-icon-pending">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="ops-status-badge status-pending" style="font-size: 14px; padding: 8px 16px;">Pending</span>
                <p class="ops-status-desc">Menunggu konfirmasi pembayaran</p>
                @break
                @case('booked')
                <div class="status-icon status-icon-booked">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="ops-status-badge status-booked" style="font-size: 14px; padding: 8px 16px;">Booked</span>
                <p class="ops-status-desc">Booking sudah terkonfirmasi</p>
                @break
                @case('done')
                <div class="status-icon status-icon-done">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="ops-status-badge status-done" style="font-size: 14px; padding: 8px 16px;">Selesai</span>
                <p class="ops-status-desc">Booking telah selesai</p>
                @break
                @case('cancelled')
                <div class="status-icon status-icon-cancelled">
                    <i class="fas fa-times-circle"></i>
                </div>
                <span class="ops-status-badge status-cancelled"
                    style="font-size: 14px; padding: 8px 16px;">Dibatalkan</span>
                <p class="ops-status-desc">Booking telah dibatalkan</p>
                @break
                @endswitch
            </div>
        </div>

        <!-- Panel Aksi — PENDING -->
        @if($booking->status_booking === 'pending')
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-cash-register mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Konfirmasi Pembayaran</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <p class="ops-payment-desc">
                    Konfirmasi jika pembayaran cash sudah diterima dari customer.
                </p>
                <div class="ops-payment-amount-box">
                    <div class="ops-payment-amount-label">Total Tagihan</div>
                    <div class="ops-payment-amount-value">
                        Rp {{ number_format($booking->harga, 0, ',', '.') }}
                    </div>
                </div>
                <form method="POST" action="{{ route('operational.booking.konfirmasiPembayaran', $booking->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ops-btn-aksi-full btn-detail btn-confirm-payment">
                        <i class="fas fa-check mr-1"></i> Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Panel Aksi — BATALKAN (hanya saat pending) -->
        @if($booking->status_booking === 'pending')
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-times-circle mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Batalkan Booking</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <p class="ops-payment-desc">
                    Batalkan booking jika customer tidak jadi atau tidak hadir.
                </p>
                <form method="POST" action="{{ route('operational.booking.cancel', $booking->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ops-btn-aksi-full btn-cancel-booking btn-cancel-confirm">
                        <i class="fas fa-times mr-1"></i> Batalkan Booking
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Panel Aksi — BOOKED -->
        @if($booking->status_booking === 'booked')
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header" style="border-bottom-color: #22c55e;">
                <i class="fas fa-check-circle mr-2" style="color: #86efac;"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Selesaikan Booking</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <p class="ops-payment-desc">
                    Tandai booking sebagai selesai jika customer sudah selesai bermain.
                </p>
                <form method="POST" action="{{ route('operational.booking.selesaikan', $booking->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ops-btn-aksi-full btn-complete btn-selesaikan">
                        <i class="fas fa-check mr-1"></i> Selesaikan Booking
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Konfirmasi Pembayaran
    document.querySelector('.btn-confirm-payment')?.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Pembayaran?',
            text: 'Pastikan pembayaran cash sudah diterima.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#94a3b8'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Selesaikan Booking
    document.querySelector('.btn-selesaikan')?.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            icon: 'question',
            title: 'Selesaikan Booking?',
            text: 'Booking akan ditandai sebagai selesai.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#94a3b8'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Batalkan Booking
    document.querySelector('.btn-cancel-confirm')?.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            icon: 'warning',
            title: 'Batalkan Booking?',
            text: 'Booking akan dibatalkan dan tidak bisa diubah kembali.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush