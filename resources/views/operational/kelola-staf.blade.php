@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('title', 'Kelola Staf | XPLAY Games')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Kelola Staf</h1>
    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahStafModal">
        <i class="fas fa-plus mr-1"></i> Tambah Staf
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<!-- Filter -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.kelola-staf') }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="ops-filter-label">Cari Staf</label>
                    <input type="text" name="search" class="form-control ops-input"
                        placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.kelola-staf') }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card ops-card">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-users-cog mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Staf</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $stafs->total() }} staf</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="120">Bergabung</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stafs as $index => $staf)
                    <tr>
                        <td class="ops-text-muted-sm">{{ ($stafs->currentPage() - 1) * $stafs->perPage() + $index + 1 }}
                        </td>
                        <td>
                            <span class="ops-table-username">{{ $staf->name }}</span>
                        </td>
                        <td>
                            <span class="ops-text-muted-sm">{{ $staf->email }}</span>
                        </td>
                        <td>
                            <span class="ops-date-text">{{ $staf->created_at->format('d M Y') }}</span>
                        </td>
                        <td>
                            <div class="ops-aksi-group">
                                <button type="button" class="ops-btn-aksi btn-detail btn-edit" data-id="{{ $staf->id }}"
                                    data-name="{{ $staf->name }}" data-email="{{ $staf->email }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete"
                                    data-id="{{ $staf->id }}" data-name="{{ $staf->name }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="ops-empty-state">Belum ada staf terdaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stafs->hasPages())
    <div class="ops-pagination">
        {{ $stafs->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah -->
<div class="modal fade ops-modal" id="tambahStafModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('operational.kelola-staf.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Staf</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">

                    {{-- Nama --}}
                    <div class="form-group">
                        <label class="ops-filter-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="tambahName" class="form-control ops-input">
                        <small class="text-danger d-none" id="tambahName-error"></small>
                    </div>
                    {{-- Email --}}
                    <div class="form-group">
                        <label class="ops-filter-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="tambahEmail" class="form-control ops-input">
                        <small class="text-danger d-none" id="tambahEmail-error"></small>
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label class="ops-filter-label">Password <span class="text-danger">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="tambahPassword" class="form-control ops-input"
                                autocomplete="new-password">
                            <button type="button" class="btn-toggle-password" data-target="tambahPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-danger d-none" id="tambahPassword-error"></small>
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

<!-- Modal Edit -->
<div class="modal fade ops-modal" id="editStafModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Staf</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label class="ops-filter-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control ops-input">
                        <small class="text-danger d-none" id="editName-error"></small>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="editEmail" class="form-control ops-input">
                        <small class="text-danger d-none" id="editEmail-error"></small>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Password Baru <span class="ops-text-muted-sm">(kosongkan jika
                                tidak diubah)</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="editPassword" class="form-control ops-input">
                            <button type="button" class="btn-toggle-password" data-target="editPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-danger d-none" id="editPassword-error"></small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn-back" data-dismiss="modal">Batal</button>
                    <button type="submit" class="ops-btn-aksi-full btn-tambah">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="{{ asset('js/operational/kelola-staf.js') }}"></script>
@endpush

@endsection