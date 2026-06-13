@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/checksheet.css') }}">
@endpush

@section('title', 'Detail Checksheet | XPLAY Games')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 ops-page-title">Detail Checksheet</h1>
        <p class="ops-info-sub mb-0">Nomor: <span class="checksum-code">{{ $checksheetHeader->checksum }}</span></p>
    </div>
    <a href="{{ route('operational.checksheet.riwayat') }}" class="ops-btn-back">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-info-circle mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Informasi</h6>
    </div>
    <div class="card-body ops-show-card-body">
        <table class="ops-info-table mb-0">
            <tr>
                <td class="ops-info-label">Tanggal</td>
                <td class="ops-info-value">{{ \Carbon\Carbon::parse($checksheetHeader->date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="ops-info-label">Shift</td>
                <td class="ops-info-value">{{ ucfirst($checksheetHeader->shift) }}</td>
            </tr>
            <tr>
                <td class="ops-info-label">Petugas</td>
                <td class="ops-info-value ops-table-username">{{ $checksheetHeader->user->name }}</td>
            </tr>
            <tr>
                <td class="ops-info-label">Status</td>
                <td class="ops-info-value">
                    @if($checksheetHeader->status === 'completed')
                    <span class="ops-status-badge status-completed">Completed</span>
                    @else
                    <span class="ops-status-badge status-pending">Draft</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="card ops-card">
    <div class="card-header ops-card-header">
        <i class="fas fa-check-square mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Item yang Diceklist</h6>
    </div>
    <div class="card-body p-0">
        <table class="table ops-table mb-0">
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Frekuensi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($checksheetHeader->details as $detail)
                <tr>
                    <td><span class="ops-table-item-name">{{ $detail->item->name }}</span></td>
                    <td>
                        <span class="ops-status-badge status-disewa">{{ $detail->item->frequency }}</span>
                    </td>
                    <td>
                        <span class="ops-status-badge status-completed">Done</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="ops-empty-state">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection