@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/checksheet.css') }}">
@endpush

@section('title', 'Kelola Checksheet | XPLAY Games')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Kelola Checksheet</h1>

    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahItemModal">
        <i class="fas fa-plus mr-1"></i> Tambah Item
    </button>
</div>

<!-- Alert -->
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
        <form method="GET" action="{{ route('operational.checksheet.manage') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Cari Item</label>
                    <input type="text" name="search" class="form-control ops-input" placeholder="Cari item..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Frekuensi</label>
                    <select name="frekuensi" class="form-control ops-input">
                        <option value="">Semua Frekuensi</option>
                        <option value="daily" {{ request('frekuensi') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="biweekly" {{ request('frekuensi') == 'biweekly' ? 'selected' : '' }}>2 Mingguan</option>
                        <option value="monthly" {{ request('frekuensi') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Shift</label>
                    <select name="shift" class="form-control ops-input">
                        <option value="">Semua Shift</option>
                        <option value="pagi" {{ request('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                        <option value="malam" {{ request('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
                        <option value="semua" {{ request('shift') == 'semua' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Status</label>
                    <select name="status" class="form-control ops-input">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.checksheet.manage') }}" class="btn ops-btn-reset">
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
            <i class="fas fa-list mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Item</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $items->count() }} item</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Item</th>
                        <th width="120">Frekuensi</th>
                        <th width="100">Shift</th>
                        <th width="80">Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                        <td><span class="ops-table-item-name">{{ $item->name }}</span></td>
                        <td>
                            @switch($item->frequency)
                            @case('daily')<span class="ops-status-badge status-pending">Harian</span>@break
                            @case('biweekly')<span class="ops-status-badge status-extended">2 Mingguan</span>@break
                            @case('monthly')<span class="ops-status-badge status-disewa">Bulanan</span>@break
                            @endswitch
                        </td>
                        <td><span class="ops-table-shift">{{ ucfirst($item->shift) }}</span></td>
                        <td>
                            @if($item->is_active)
                            <span class="ops-status-badge status-completed">Aktif</span>
                            @else
                            <span class="ops-status-badge status-cancelled">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="ops-aksi-group">
                                <button type="button" class="ops-btn-aksi btn-edit-checksheet"
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                    data-frekuensi="{{ $item->frequency }}" data-shift="{{ $item->shift }}"
                                    data-active="{{ $item->is_active }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-checksheet"
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="ops-empty-state">Belum ada item checksheet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade ops-modal" id="tambahItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('operational.checksheet.item.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item Checksheet</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Nama Item <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control ops-input" required>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Frekuensi <span class="text-danger">*</span></label>
                        <select name="frequency" class="form-control ops-input" required>
                            <option value="daily">Harian</option>
                            <option value="biweekly">2 Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Shift <span class="text-danger">*</span></label>
                        <select name="shift" class="form-control ops-input" required>
                            <option value="pagi">Pagi</option>
                            <option value="malam">Malam</option>
                            <option value="semua">Semua</option>
                        </select>
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
<div class="modal fade ops-modal" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item Checksheet</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Nama Item <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control ops-input" required>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Frekuensi <span class="text-danger">*</span></label>
                        <select name="frequency" id="editFrequency" class="form-control ops-input" required>
                            <option value="daily">Harian</option>
                            <option value="biweekly">2 Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Shift <span class="text-danger">*</span></label>
                        <select name="shift" id="editShift" class="form-control ops-input" required>
                            <option value="pagi">Pagi</option>
                            <option value="malam">Malam</option>
                            <option value="semua">Semua</option>
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" name="is_active" id="editIsActive" value="1" class="mr-2" style="width: 18px; height: 18px; accent-color: #6366f1;">
                            <label for="editIsActive" style="color: #cbd5e1; font-size: 13px;">Item Aktif</label>
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/operational/checksheet.js') }}"></script>
@endpush

@endsection