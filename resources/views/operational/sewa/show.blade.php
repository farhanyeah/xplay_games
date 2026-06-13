@extends('layouts.operational')

@section('title', 'Detail Sewa | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/data-sewa.css') }}">
@endpush

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Detail Sewa</h1>
    <a href="{{ route('operational.sewa.index') }}" class="ops-btn-back">
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

        <!-- Info Customer & Unit -->
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-file-invoice mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">
                    Informasi Transaksi — {{ $sewa->transaction_code }}
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
                                <td class="ops-info-value">{{ $sewa->nama }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">No HP</td>
                                <td class="ops-info-value">{{ $sewa->no_hp }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Alamat</td>
                                <td class="ops-info-value">{{ $sewa->alamat }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Jenis Jaminan</td>
                                <td class="ops-info-value">
                                    {{ $sewa->guarantee_type }}
                                    @if($sewa->guarantee_other)
                                    <span class="ops-info-sub">({{ $sewa->guarantee_other }})</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Pembayaran</td>
                                <td class="ops-info-value">{{ ucfirst($sewa->pembayaran) }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Status Bayar</td>
                                <td class="ops-info-value">
                                    @if($sewa->payment_status === 'paid')
                                    <span class="ops-status-badge status-completed">Lunas</span>
                                    @else
                                    <span class="ops-status-badge status-cancelled">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Info Unit & Waktu -->
                    <div class="col-md-6">
                        <div class="ops-info-section-title">Data Unit & Waktu</div>
                        <table class="ops-info-table">
                            <tr>
                                <td class="ops-info-label">Unit</td>
                                <td class="ops-info-value">
                                    {{ $sewa->unitSewa->kode_unit }}
                                    <span class="ops-info-sub">— {{ $sewa->unitSewa->jenisUnit->tipe }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Durasi</td>
                                <td class="ops-info-value">
                                    @if($sewa->paket_id)
                                    {{ $sewa->paket->durasi_hari }} Hari
                                    @else
                                    {{ $sewa->durasi_custom }} Hari
                                    <span class="ops-info-sub">(Custom)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Tanggal Mulai</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Tanggal Selesai</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Dibuat</td>
                                <td class="ops-info-value">
                                    {{ \Carbon\Carbon::parse($sewa->created_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Breakdown Harga -->
                <div class="harga-breakdown mt-4">
                    <div class="harga-item">
                        <div class="harga-item-label">Harga Sewa</div>
                        <div class="harga-item-value">
                            Rp {{ number_format($sewa->harga_sewa, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="harga-item">
                        <div class="harga-item-label">Harga Jaminan</div>
                        <div class="harga-item-value">
                            Rp {{ number_format($sewa->harga_jaminan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="harga-item harga-item-total">
                        <div class="harga-item-label">Total Harga</div>
                        <div class="harga-item-value-total">
                            Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Completed (jika sudah selesai) -->
        @if($sewa->status_sewa === 'completed')
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header" style="border-bottom-color: #22c55e;">
                <i class="fas fa-check-double mr-2" style="color: #86efac;"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Hasil Penyelesaian Sewa</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <table class="ops-info-table">
                    <tr>
                        <td class="ops-info-label">Jaminan Dikembalikan</td>
                        <td class="ops-info-value" style="color: #16a34a; font-weight: 700;">
                            Rp {{ number_format($sewa->jaminan_balik, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="ops-info-label">Jaminan Hangus</td>
                        <td class="ops-info-value" style="color: #dc2626; font-weight: 700;">
                            Rp {{ number_format($sewa->harga_jaminan - $sewa->jaminan_balik, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($sewa->keterangan)
                    <tr>
                        <td class="ops-info-label">Keterangan</td>
                        <td class="ops-info-value">{{ $sewa->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif

    </div>

    <!-- Kolom Kanan — Panel Aksi -->
    <div class="col-lg-4">

        <!-- Status Badge -->
        <div class="card ops-card mb-4">
            <div class="card-body ops-show-card-body text-center py-4">
                @switch($sewa->status_sewa)
                @case('pending')
                <div class="status-icon status-icon-pending">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="ops-status-badge status-pending" style="font-size: 14px; padding: 8px 16px;">Pending</span>
                <p class="status-desc" style="color: #94a3b8; font-size: 12px; margin-top: 8px;">Menunggu konfirmasi
                    dari staf</p>
                @break
                @case('disewa')
                <div class="status-icon status-icon-disewa">
                    <i class="fas fa-gamepad"></i>
                </div>
                <span class="ops-status-badge status-disewa" style="font-size: 14px; padding: 8px 16px;">Sedang
                    Disewa</span>
                <p class="status-desc" style="color: #94a3b8; font-size: 12px; margin-top: 8px;">Unit PS sedang
                    digunakan customer</p>
                @break
                @case('extended')
                <div class="status-icon status-icon-extended">
                    <i class="fas fa-history"></i>
                </div>
                <span class="ops-status-badge status-extended"
                    style="font-size: 14px; padding: 8px 16px;">Extended</span>
                <p class="status-desc" style="color: #94a3b8; font-size: 12px; margin-top: 8px;">Durasi sewa telah
                    diperpanjang</p>
                @break
                @case('completed')
                <div class="status-icon status-icon-completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="ops-status-badge status-completed"
                    style="font-size: 14px; padding: 8px 16px;">Selesai</span>
                <p class="status-desc" style="color: #94a3b8; font-size: 12px; margin-top: 8px;">Sewa telah selesai</p>
                @break
                @case('cancelled')
                <div class="status-icon status-icon-cancelled">
                    <i class="fas fa-times-circle"></i>
                </div>
                <span class="ops-status-badge status-cancelled"
                    style="font-size: 14px; padding: 8px 16px;">Dibatalkan</span>
                <p class="status-desc" style="color: #94a3b8; font-size: 12px; margin-top: 8px;">Transaksi dibatalkan
                </p>
                @break
                @endswitch
            </div>
        </div>

        <!-- Panel Aksi -->

        {{-- PENDING — Konfirmasi Pembayaran atau Cancel --}}
        @if($sewa->status_sewa === 'pending')
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-cash-register mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Konfirmasi Pembayaran</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <p class="info-payment-desc">
                    Customer belum melakukan pembayaran. Konfirmasi jika pembayaran cash sudah diterima.
                </p>

                <div class="payment-amount-box">
                    <div class="payment-amount-label">Total Tagihan</div>
                    <div class="payment-amount-value">
                        Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}
                    </div>
                </div>

                <form method="POST" action="{{ route('operational.sewa.konfirmasiPembayaran', $sewa->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ops-btn-aksi-full btn-detail mb-2 btn-confirm-payment">
                        <i class="fas fa-check mr-1"></i> Konfirmasi Pembayaran
                    </button>
                </form>

                <form method="POST" action="{{ route('operational.sewa.cancel', $sewa->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ops-btn-aksi-full btn-cancel-sewa btn-cancel-confirm">
                        <i class="fas fa-times mr-1"></i> Batalkan Sewa
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- DISEWA atau EXTENDED — Selesaikan Sewa --}}
        @if(in_array($sewa->status_sewa, ['disewa', 'extended']))
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header" style="border-bottom-color: #22c55e;">
                <i class="fas fa-check-circle mr-2" style="color: #86efac;"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Selesaikan Sewa</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <form method="POST" action="{{ route('operational.sewa.complete', $sewa->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label class="ops-filter-label">
                            Jaminan Dikembalikan
                            <span class="filter-label-hint">Isi 0 jika jaminan hangus semua</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text ops-input-prefix">Rp</span>
                            </div>
                            <input type="number" name="jaminan_balik" class="form-control ops-input" min="0"
                                max="{{ $sewa->harga_jaminan }}" value="{{ $sewa->harga_jaminan }}"
                                style="border-radius: 0 8px 8px 0 !important;">
                        </div>
                        <small class="jaminan-hint">
                            Jaminan awal: Rp {{ number_format($sewa->harga_jaminan, 0, ',', '.') }}
                        </small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="ops-filter-label">
                            Keterangan
                            <span class="filter-label-hint">Opsional — isi jika ada denda</span>
                        </label>
                        <textarea name="keterangan" class="form-control ops-input" rows="3" style="height: auto;"
                            placeholder="Contoh: Unit PS telat dikembalikan 1 jam">{{ $sewa->keterangan }}</textarea>
                    </div>

                    <button type="submit" class="ops-btn-aksi-full btn-complete">
                        <i class="fas fa-check mr-1"></i> Selesaikan Sewa
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- EXTENDED — Selesaikan atau Batalkan --}}
        @if($sewa->status_sewa === 'extended')
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-times-circle mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Batalkan Sewa</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <form method="POST" action="{{ route('operational.sewa.cancel', $sewa->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ops-btn-aksi-full btn-cancel-sewa btn-cancel-confirm">
                        <i class="fas fa-times mr-1"></i> Batalkan Sewa
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

    // Batalkan Sewa
    document.querySelector('.btn-cancel-confirm')?.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            icon: 'warning',
            title: 'Batalkan Sewa?',
            text: 'Transaksi ini akan dibatalkan dan tidak bisa diubah kembali.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Selesaikan Sewa
    document.querySelector('.btn-complete')?.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');
        const jaminan = form.querySelector('input[name="jaminan_balik"]').value;

        Swal.fire({
            icon: 'question',
            title: 'Selesaikan Sewa?',
            html: `Jaminan dikembalikan: Rp ${new Intl.NumberFormat('id-ID').format(jaminan)}`,
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#94a3b8'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush