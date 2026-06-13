@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/checksheet.css') }}">
@endpush

@section('title', 'Riwayat Checksheet | XPLAY Games')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Riwayat Checksheet</h1>
</div>

<!-- Alert Success -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<!-- Filter -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Pencarian</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control ops-input" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Shift</label>
                    <select name="shift" class="form-control ops-input">
                        <option value="">Semua Shift</option>
                        <option value="pagi" {{ request('shift') === 'pagi' ? 'selected' : '' }}>Pagi</option>
                        <option value="malam" {{ request('shift') === 'malam' ? 'selected' : '' }}>Malam</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Status</label>
                    <select name="status" class="form-control ops-input">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <div class="d-flex">
                        <button type="submit" class="btn ops-btn-filter mr-2">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                        <a href="{{ route('operational.checksheet.riwayat') }}" class="btn ops-btn-reset">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-clipboard-check mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Riwayat Checksheet</h6>
        </div>
        <span class="ops-badge-total">
            Total: {{ $riwayatChecksheets->count() }} data
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Shift</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatChecksheets as $index => $r)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                        <td>
                            <span class="checksum-code">{{ $r->checksum }}</span>
                        </td>
                        <td class="ops-date-text">{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</td>
                        <td>
                            @if($r->shift === 'pagi')
                            <span class="ops-status-badge status-disewa">Pagi</span>
                            @else
                            <span class="ops-status-badge status-extended">Malam</span>
                            @endif
                        </td>
                        <td><span class="ops-table-username">{{ $r->user->name }}</span></td>
                        <td>
                            @if($r->status === 'completed')
                            <span class="ops-status-badge status-completed">Selesai</span>
                            @else
                            <span class="ops-status-badge status-pending">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="ops-aksi-group">
                                <a href="{{ route('operational.checksheet.show', $r->id) }}" class="ops-btn-aksi btn-detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="ops-empty-state">
                            Tidak ada riwayat checksheet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection