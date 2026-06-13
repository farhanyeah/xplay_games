@extends('layouts.operational')

@section('title', 'Detail Billing | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Detail Billing</h1>
    <a href="{{ route('operational.billing.history') }}" class="ops-btn-back">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">

    {{-- KOLOM KIRI --}}
    <div class="col-lg-8">

        {{-- Info Utama --}}
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-gamepad mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">
                    Informasi Billing — BL-{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}
                </h6>
            </div>
            <div class="card-body ops-show-card-body">
                <div class="row">

                    {{-- Data Customer --}}
                    <div class="col-md-6">
                        <div class="ops-info-section-title">Data Customer</div>
                        <table class="ops-info-table">
                            <tr>
                                <td class="ops-info-label">Nama</td>
                                <td class="ops-info-value">{{ $billing->nama_customer }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Metode Bayar</td>
                                <td class="ops-info-value">
                                    <span class="booking-tipe-badge tipe-perjam">
                                        {{ strtoupper($billing->metode_bayar) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Status Bayar</td>
                                <td class="ops-info-value">
                                    @if($billing->status_bayar === 'paid')
                                        <span class="ops-status-badge status-done">Lunas</span>
                                    @else
                                        <span class="ops-status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @if($billing->catatan)
                            <tr>
                                <td class="ops-info-label">Catatan</td>
                                <td class="ops-info-value">{{ $billing->catatan }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    {{-- Data Sesi --}}
                    <div class="col-md-6">
                        <div class="ops-info-section-title">Data Sesi</div>
                        <table class="ops-info-table">
                            <tr>
                                <td class="ops-info-label">Unit</td>
                                <td class="ops-info-value">
                                    {{ $billing->unit->nama_unit ?? '-' }}
                                    <span class="ops-info-sub">— {{ $billing->unit->jenisUnit->tipe ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Lantai</td>
                                <td class="ops-info-value">Lantai {{ $billing->unit->lantai ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Paket</td>
                                <td class="ops-info-value">
                                    @if($billing->paketHarga)
                                        <span class="booking-tipe-badge tipe-perjam">
                                            {{ $billing->paketHarga->jumlah_jam }} Jam
                                        </span>
                                    @elseif($billing->paketKhusus)
                                        <span class="booking-tipe-badge tipe-khusus">
                                            {{ $billing->paketKhusus->nama_paket }}
                                        </span>
                                    @else
                                        <span class="ops-text-muted-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Durasi</td>
                                <td class="ops-info-value">{{ $billing->jumlah_jam }} Jam</td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Jam Mulai</td>
                                <td class="ops-info-value">
                                    {{ $billing->jam_mulai ? $billing->jam_mulai->format('H:i, d M Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ops-info-label">Jam Selesai</td>
                                <td class="ops-info-value">
                                    {{ $billing->jam_selesai ? $billing->jam_selesai->format('H:i, d M Y') : '-' }}
                                </td>
                            </tr>
                            @if($billing->total_pause_menit > 0)
                            <tr>
                                <td class="ops-info-label">Total Pause</td>
                                <td class="ops-info-value">{{ $billing->total_pause_menit }} Menit</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Breakdown Harga --}}
                <div class="ops-harga-breakdown ops-harga-breakdown-2 mt-4">
                    <div class="ops-harga-item">
                        <div class="ops-harga-item-label">Harga Awal</div>
                        <div class="ops-harga-item-value">
                            Rp {{ number_format($billing->harga_awal, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="ops-harga-item ops-harga-item-total">
                        <div class="ops-harga-item-label">Harga Final</div>
                        <div class="ops-harga-item-value-total">
                            Rp {{ number_format($billing->harga_final, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- History Extend --}}
        @if($billing->extends->count() > 0)
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-plus-circle mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">
                    History Extend ({{ $billing->extends->count() }}x)
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table ops-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tambah Jam</th>
                                <th>Harga Tambah</th>
                                <th>Metode Bayar</th>
                                <th>Status Bayar</th>
                                <th>Dicatat Oleh</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billing->extends as $index => $ext)
                            <tr>
                                <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                                <td class="ops-date-text">{{ $ext->jumlah_jam_tambah }} Jam</td>
                                <td>
                                    <span class="ops-harga-text">
                                        Rp {{ number_format($ext->harga_tambah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="booking-tipe-badge tipe-perjam">
                                        {{ strtoupper($ext->metode_bayar) }}
                                    </span>
                                </td>
                                <td>
                                    @if($ext->status_bayar === 'paid')
                                        <span class="ops-status-badge status-done">Lunas</span>
                                    @else
                                        <span class="ops-status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td class="ops-table-username">{{ $ext->createdBy->name ?? '-' }}</td>
                                <td class="ops-date-text">{{ $ext->created_at->format('H:i, d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- History Refund --}}
        @if($billing->refunds->count() > 0)
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-undo mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">
                    History Refund ({{ $billing->refunds->count() }}x)
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table ops-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nominal Refund</th>
                                <th>Alasan</th>
                                <th>Dicatat Oleh</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billing->refunds as $index => $refund)
                            <tr>
                                <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                                <td>
                                    <span class="ops-harga-text">
                                        Rp {{ number_format($refund->nominal_refund, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="ops-table-alasan">{{ $refund->alasan }}</td>
                                <td class="ops-table-username">{{ $refund->createdBy->name ?? '-' }}</td>
                                <td class="ops-date-text">{{ $refund->created_at->format('H:i, d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- History Pindah Unit --}}
        @if($billing->pindahUnit->count() > 0)
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-exchange-alt mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">
                    History Pindah Unit ({{ $billing->pindahUnit->count() }}x)
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table ops-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Dari Unit</th>
                                <th>Ke Unit</th>
                                <th>Alasan</th>
                                <th>Dipindah Oleh</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billing->pindahUnit as $index => $pindah)
                            <tr>
                                <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                                <td>
                                    <div class="ops-unit-code">{{ $pindah->dariUnit->nama_unit ?? '-' }}</div>
                                    <div class="ops-unit-type">{{ $pindah->dariUnit->jenisUnit->tipe ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="ops-unit-code">{{ $pindah->keUnit->nama_unit ?? '-' }}</div>
                                    <div class="ops-unit-type">{{ $pindah->keUnit->jenisUnit->tipe ?? '-' }}</div>
                                </td>
                                <td class="ops-table-alasan">{{ $pindah->alasan ?? '-' }}</td>
                                <td class="ops-table-username">{{ $pindah->createdBy->name ?? '-' }}</td>
                                <td class="ops-date-text">{{ $pindah->created_at->format('H:i, d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-lg-4">

        {{-- Status Sesi --}}
        <div class="card ops-card mb-4">
            <div class="card-body ops-show-card-body text-center py-4">
                @switch($billing->status_sesi)
                @case('available')
                    <div class="status-icon status-icon-booked">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="ops-status-badge status-booked" style="font-size: 14px; padding: 8px 16px;">
                        Menunggu Start
                    </span>
                    <p class="ops-status-desc">Sudah lunas, menunggu sesi dimulai</p>
                    @break
                @case('active')
                    <div class="status-icon status-icon-done">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <span class="ops-status-badge status-done" style="font-size: 14px; padding: 8px 16px;">
                        Sedang Aktif
                    </span>
                    <p class="ops-status-desc">Sesi sedang berjalan</p>
                    @break
                @case('completed')
                    <div class="status-icon status-icon-done">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="ops-status-badge status-done" style="font-size: 14px; padding: 8px 16px;">
                        Selesai
                    </span>
                    <p class="ops-status-desc">Sesi telah selesai</p>
                    @break
                @endswitch
            </div>
        </div>

        {{-- Audit --}}
        <div class="card ops-card mb-4">
            <div class="card-header ops-card-header">
                <i class="fas fa-user-clock mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Petugas</h6>
            </div>
            <div class="card-body ops-show-card-body">
                <table class="ops-info-table">
                    <tr>
                        <td class="ops-info-label">Dibuat Oleh</td>
                        <td class="ops-info-value">{{ $billing->createdBy->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="ops-info-label">Dibuat Pada</td>
                        <td class="ops-info-value">
                            {{ $billing->created_at->format('H:i, d M Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="ops-info-label">Update Oleh</td>
                        <td class="ops-info-value">{{ $billing->updatedBy->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="ops-info-label">Update Pada</td>
                        <td class="ops-info-value">
                            {{ $billing->updated_at->format('H:i, d M Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection