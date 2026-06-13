@extends('layouts.operational')

@section('title', 'Data Maintenance | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Maintenance</h1>

    <button class="ops-btn-tambah" data-toggle="modal" data-target="#createMaintenanceModal">
        <i class="fas fa-plus mr-1"></i> Tambah Maintenance
    </button>
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

<!-- Alert Error -->
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<!-- Filter -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.maintenance.index') }}">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="ops-filter-label">Cari Judul / Deskripsi</label>
                    <input type="text" name="search" class="form-control ops-input"
                        placeholder="Judul atau deskripsi..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Status Maintenance</label>
                    <select name="status" class="form-control ops-input">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

            </div>

            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.maintenance.index') }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Maintenance -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-tools mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Maintenance</h6>
        </div>
        <span class="ops-badge-total">
            Total: {{ $maintenances->total() }} Laporan
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $index => $maintenance)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $maintenances->firstItem() + $index }}</td>

                        <td>
                            <div class="ops-customer-name">{{ $maintenance->title }}</div>
                            @if($maintenance->description)
                            <div class="ops-customer-phone text-muted">
                                {{ Str::limit($maintenance->description, 50) }}
                            </div>
                            @endif
                        </td>

                        <td>
                            @switch($maintenance->status)
                            @case('open')
                            <span class="ops-status-badge status-pending">Open</span>
                            @break
                            @case('resolved')
                            <span class="ops-status-badge status-done">Resolved</span>
                            @break
                            @endswitch
                        </td>

                        <td>
                            <div class="ops-customer-name">{{ $maintenance->creator->name ?? '-' }}</div>
                        </td>

                        <td class="ops-date-text">
                            {{ $maintenance->created_at->format('d M Y') }}
                        </td>

                        <td>
                            <div class="ops-aksi-group">
                                <a href="{{ route('operational.maintenance.show', $maintenance->id) }}"
                                    class="ops-btn-aksi btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="ops-empty-state">
                            Tidak ada data maintenance ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($maintenances->hasPages())
    <div class="card-footer ops-pagination">
        <nav>
            <ul class="pagination mb-0">

                @if($maintenances->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $maintenances->previousPageUrl() }}">&laquo;</a></li>
                @endif

                @foreach($maintenances->getUrlRange(1, $maintenances->lastPage()) as $page => $url)
                @if($page == $maintenances->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach

                @if($maintenances->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $maintenances->nextPageUrl() }}">&raquo;</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif

            </ul>
        </nav>
    </div>
    @endif
</div>

<!-- Modal Create Maintenance -->
<div class="modal fade ops-modal" id="createMaintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('operational.maintenance.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Maintenance</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control ops-input" value="{{ old('title') }}" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Deskripsi</label>
                        <textarea name="description" class="form-control ops-input">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Foto</label>
                        <input type="file" name="photos[]" multiple class="form-control ops-input">
                        @error('photos')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="ops-btn-back" data-dismiss="modal">Batal</button>
                    <button type="submit" class="ops-btn-aksi-full btn-tambah">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection