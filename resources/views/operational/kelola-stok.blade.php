@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('title', 'Kelola Stok | XPLAY Games')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Kelola Stok</h1>
    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahStokModal">
        <i class="fas fa-plus mr-1"></i> Tambah Item
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
        <form method="GET" action="{{ route('operational.stok') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Cari Item</label>
                    <input type="text" name="search" class="form-control ops-input"
                        placeholder="Cari nama item..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Kategori</label>
                    <select name="kategori" class="form-control ops-input">
                        <option value="">Semua Kategori</option>
                        <option value="makanan" {{ request('kategori') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="minuman" {{ request('kategori') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.stok') }}" class="btn ops-btn-reset">
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
            <i class="fas fa-boxes mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Stok</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $stoks->total() }} item</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Item</th>
                        <th width="110">Kategori</th>
                        <th width="130">Harga</th>
                        <th width="100">Stok</th>
                        <th width="90">Satuan</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stoks as $index => $stok)
                    <tr>
                        <td class="ops-text-muted-sm">{{ ($stoks->currentPage() - 1) * $stoks->perPage() + $index + 1 }}</td>
                        <td><span class="ops-table-item-name">{{ $stok->nama }}</span></td>
                        <td>
                            @switch($stok->kategori)
                                @case('makanan')
                                    <span class="ops-status-badge status-pending">Makanan</span>
                                    @break
                                @case('minuman')
                                    <span class="ops-status-badge status-disewa">Minuman</span>
                                    @break
                                @case('lainnya')
                                    <span class="ops-status-badge status-extended">Lainnya</span>
                                    @break
                            @endswitch
                        </td>
                        <td><span class="ops-harga-text">Rp {{ number_format($stok->harga, 0, ',', '.') }}</span></td>
                        <td>
                            <span class="ops-table-item-name {{ $stok->stok <= 5 ? 'text-danger' : '' }}">
                                {{ $stok->stok }}
                            </span>
                        </td>
                        <td><span class="ops-text-muted-sm">{{ $stok->satuan }}</span></td>
                        <td>
                            <div class="ops-aksi-group">
                                <button type="button" class="ops-btn-aksi btn-detail btn-edit-stok"
                                    data-id="{{ $stok->id }}"
                                    data-nama="{{ $stok->nama }}"
                                    data-kategori="{{ $stok->kategori }}"
                                    data-harga="{{ $stok->harga }}"
                                    data-stok="{{ $stok->stok }}"
                                    data-satuan="{{ $stok->satuan }}"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-restock"
                                    data-id="{{ $stok->id }}"
                                    data-nama="{{ $stok->nama }}"
                                    data-satuan="{{ $stok->satuan }}"
                                    title="Restock">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-stok"
                                    data-id="{{ $stok->id }}"
                                    data-nama="{{ $stok->nama }}"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="ops-empty-state">Belum ada item stok</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stoks->hasPages())
    <div class="ops-pagination">
        {{ $stoks->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah -->
<div class="modal fade ops-modal" id="tambahStokModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="tambahStokForm" action="{{ route('operational.stok.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item Stok</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Nama Item <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="tambahNama" class="form-control ops-input">
                        <small class="text-danger d-none" id="tambahNama-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" id="tambahKategori" class="form-control ops-input">
                            <option value="">Pilih Kategori</option>
                            <option value="makanan">Makanan</option>
                            <option value="minuman">Minuman</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <small class="text-danger d-none" id="tambahKategori-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="tambahHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="tambahHarga-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" name="stok" id="tambahStok" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="tambahStok-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Satuan <span class="text-danger">*</span></label>
                        <select name="satuan" id="tambahSatuan" class="form-control ops-input">
                            <option value="">Pilih Satuan</option>
                            <option value="pcs">Pcs</option>
                            <option value="botol">Botol</option>
                            <option value="kaleng">Kaleng</option>
                            <option value="bungkus">Bungkus</option>
                            <option value="pak">Pak</option>
                        </select>
                        <small class="text-danger d-none" id="tambahSatuan-error"></small>
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
<div class="modal fade ops-modal" id="editStokModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editStokForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item Stok</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Nama Item <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control ops-input">
                        <small class="text-danger d-none" id="editNama-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" id="editKategori" class="form-control ops-input">
                            <option value="">Pilih Kategori</option>
                            <option value="makanan">Makanan</option>
                            <option value="minuman">Minuman</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <small class="text-danger d-none" id="editKategori-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="editHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="editHarga-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok" id="editStok" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="editStok-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Satuan <span class="text-danger">*</span></label>
                        <select name="satuan" id="editSatuan" class="form-control ops-input">
                            <option value="">Pilih Satuan</option>
                            <option value="pcs">Pcs</option>
                            <option value="botol">Botol</option>
                            <option value="kaleng">Kaleng</option>
                            <option value="bungkus">Bungkus</option>
                            <option value="pak">Pak</option>
                        </select>
                        <small class="text-danger d-none" id="editSatuan-error"></small>
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

<!-- Modal Restock -->
<div class="modal fade ops-modal" id="restockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="restockForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Restock Item</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="ops-payment-desc">Tambah stok untuk: <strong id="restockNama"></strong></p>
                    <div class="form-group">
                        <label class="ops-filter-label">Jumlah Tambah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="restockJumlah" class="form-control ops-input" min="1">
                        <small class="text-danger d-none" id="restockJumlah-error"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn-back" data-dismiss="modal">Batal</button>
                    <button type="submit" class="ops-btn-aksi-full btn-tambah">
                        <i class="fas fa-plus-circle mr-1"></i> Restock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete -->
<form id="deleteStokForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="{{ asset('js/operational/kelola-stok.js') }}"></script>
@endpush

@endsection