@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('title', 'Penjualan | XPLAY Games')

@section('content')

<!-- Pendapatan Hari Ini -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Penjualan</h1>
    <div class="pendapatan-hari-ini">
        <div class="pendapatan-label">
            <i class="fas fa-coins mr-1"></i> Pendapatan Penjualan Hari Ini
        </div>
        <div class="pendapatan-value">
            Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- Tombol Transaksi Baru -->
<div class="mb-4">
    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahTransaksiModal">
        <i class="fas fa-plus mr-1"></i> Transaksi Baru
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
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
        <form method="GET" action="{{ route('operational.penjualan') }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Cari Item</label>
                    <input type="text" name="search" class="form-control ops-input" placeholder="Cari nama item..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Metode Pembayaran</label>
                    <select name="metode" class="form-control ops-input">
                        <option value="">Semua Metode</option>
                        <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="midtrans" {{ request('metode') == 'midtrans' ? 'selected' : '' }}>Midtrans
                        </option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Status Pembayaran</label>
                    <select name="status" class="form-control ops-input">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control ops-input" value="{{ $tanggal }}">
                </div>
                
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.penjualan') }}" class="btn ops-btn-reset">
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
            <i class="fas fa-cash-register mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">
                Data Penjualan — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            </h6>
        </div>
        <span class="ops-badge-total">Total: {{ $penjualans->total() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Kode Transaksi</th>
                        <th>Item</th>
                        <th width="130">Total</th>
                        <th width="100">Metode</th>
                        <th width="100">Status</th>
                        <th width="100">Petugas</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $index => $penjualan)
                    <tr>
                        <td class="ops-text-muted-sm">
                            {{ ($penjualans->currentPage() - 1) * $penjualans->perPage() + $index + 1 }}</td>
                        <td>
                            <span class="ops-transaction-code">{{ $penjualan->transaction_code }}</span>
                        </td>
                        <td>
                            @foreach($penjualan->items as $item)
                            <div class="ops-text-muted-sm">{{ $item->nama_item }} x{{ $item->jumlah }}</div>
                            @endforeach
                        </td>
                        <td><span class="ops-harga-text">Rp
                                {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span></td>
                        <td>
                            @if($penjualan->metode_pembayaran === 'cash')
                            <span class="ops-status-badge status-completed">Cash</span>
                            @else
                            <span class="ops-status-badge status-disewa">Midtrans</span>
                            @endif
                        </td>
                        <td>
                            @if($penjualan->payment_status === 'paid')
                            <span class="ops-status-badge status-completed">Paid</span>
                            @else
                            <span class="ops-status-badge status-pending">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <span class="ops-table-username">{{ $penjualan->createdBy->name ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="ops-aksi-group">
                                @if($penjualan->payment_status === 'unpaid')
                                @if($penjualan->metode_pembayaran === 'cash')
                                <form method="POST"
                                    action="{{ route('operational.penjualan.konfirmasiPembayaran', $penjualan->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="ops-btn-aksi btn-complete" title="Konfirmasi Bayar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button" class="ops-btn-aksi btn-detail btn-midtrans"
                                    data-token="{{ $penjualan->midtrans_token }}" data-id="{{ $penjualan->id }}"
                                    title="Bayar Midtrans">
                                    <i class="fas fa-credit-card"></i>
                                </button>
                                @endif
                                @endif

                                @if(auth()->user()->role === 'owner' || $penjualan->payment_status === 'unpaid')
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-penjualan"
                                    data-id="{{ $penjualan->id }}" data-kode="{{ $penjualan->transaction_code }}"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @else
                                <span class="ops-text-muted-sm">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="ops-empty-state">Belum ada transaksi penjualan hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($penjualans->hasPages())
    <div class="card-footer ops-pagination">
        <nav>
            <ul class="pagination mb-0">

                @if($penjualans->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $penjualans->previousPageUrl() }}">&laquo;</a></li>
                @endif

                @foreach($penjualans->getUrlRange(1, $penjualans->lastPage()) as $page => $url)
                @if($page == $penjualans->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach

                @if($penjualans->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $penjualans->nextPageUrl() }}">&raquo;</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif

            </ul>
        </nav>
    </div>
    @endif

</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade ops-modal" id="tambahTransaksiModal" tabindex="-1">
    <div class="modal-dialog" style="max-width: 560px;">
        <div class="modal-content">
            <form method="POST" id="tambahTransaksiForm" action="{{ route('operational.penjualan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Transaksi Baru</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">

                    <!-- Item List -->
                    <div class="form-group">
                        <label class="ops-filter-label">Item yang Dibeli <span class="text-danger">*</span></label>
                        <div id="itemList">
                            <!-- Item row pertama -->
                            <div class="item-row d-flex mb-2" style="gap:8px;">
                                <select name="items[0][stok_id]" class="form-control ops-input select-item"
                                    style="flex:2;">
                                    <option value="">Pilih Item</option>
                                    @foreach($stoks as $stok)
                                    <option value="{{ $stok->id }}" 
                                        data-harga="{{ $stok->harga }}"
                                        data-stok="{{ $stok->stok }}"
                                        data-nama="{{ $stok->nama }}">
                                        {{ $stok->nama }} (Stok: {{ $stok->stok }}) — Rp {{ number_format($stok->harga, 0, ',', '.') }}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[0][jumlah]" class="form-control ops-input input-jumlah"
                                    placeholder="Qty" min="1" style="flex:1;">
                                <button type="button" class="ops-btn-aksi btn-hapus btn-remove-item"
                                    style="flex-shrink:0;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-danger d-none" id="itemList-error"></small>
                    </div>

                    <button type="button" id="btnTambahItem" class="ops-btn-reset mb-3" style="font-size:12px;">
                        <i class="fas fa-plus mr-1"></i> Tambah Item
                    </button>

                    <!-- Total -->
                    <div class="ops-payment-amount-box mb-3">
                        <div class="ops-payment-amount-label">Total Harga</div>
                        <div class="ops-payment-amount-value" id="totalHarga">Rp 0</div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="form-group">
                        <label class="ops-filter-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode_pembayaran" id="metodePembayaran" class="form-control ops-input">
                            <option value="">Pilih Metode</option>
                            <option value="cash">Cash</option>
                            <option value="midtrans">Midtrans</option>
                        </select>
                        <small class="text-danger d-none" id="metodePembayaran-error"></small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="ops-btn-back" data-dismiss="modal">Batal</button>
                    <button type="submit" class="ops-btn-aksi-full btn-tambah">
                        <i class="fas fa-save mr-1"></i> Buat Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete -->
<form id="deletePenjualanForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="{{ asset('js/operational/penjualan.js') }}"></script>
@endpush

@endsection