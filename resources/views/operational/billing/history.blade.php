@extends('layouts.operational')

@section('title', 'Histori Billing | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Histori Billing</h1>

    <div class="pendapatan-hari-ini">
        <div class="pendapatan-label">
            <i class="fas fa-coins mr-1"></i> Pendapatan Billing Hari Ini
        </div>
        <div class="pendapatan-value">
            Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>

    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.billing.history') }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Cari Billing / Customer / Unit</label>
                    <input type="text" name="search" class="form-control ops-input"
                        placeholder="BL-000001 / nama / unit..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Unit</label>
                    <select name="unit_id" class="form-control ops-input">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama_unit }} - {{ $unit->jenisUnit->tipe ?? '-' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="ops-filter-label">Metode Bayar</label>
                    <select name="metode_bayar" class="form-control ops-input">
                        <option value="">Semua</option>
                        <option value="cash" {{ request('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="midtrans" {{ request('metode_bayar') == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="ops-filter-label">Status Bayar</label>
                    <select name="status_bayar" class="form-control ops-input">
                        <option value="">Semua</option>
                        <option value="paid" {{ request('status_bayar') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="pending" {{ request('status_bayar') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="ops-filter-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal" class="form-control ops-input"
                         value="{{ request('tanggal') ?? now()->toDateString() }}">
                </div>

            </div>

            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>

                <a href="{{ route('operational.billing.history') }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card ops-card mb-4">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-history mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Histori Billing</h6>
        </div>

        <span class="ops-badge-total">
            Total: {{ $billings->total() }} transaksi
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Billing</th>
                        <th>Customer</th>
                        <th>Unit</th>
                        <th>Paket</th>
                        <th>Durasi</th>
                        <th>Waktu Sesi</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($billings as $index => $billing)
                    <tr>
                        <td class="ops-text-muted-sm">
                            {{ $billings->firstItem() + $index }}
                        </td>

                        <td>
                            <span class="ops-transaction-code">
                                BL-{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td>
                            <div class="ops-customer-name">{{ $billing->nama_customer }}</div>
                        </td>

                        <td>
                            <div class="ops-unit-code">{{ $billing->unit->nama_unit ?? '-' }}</div>
                            <div class="ops-unit-type">{{ $billing->unit->jenisUnit->tipe ?? '-' }}</div>
                        </td>

                        <td>
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

                        <td class="ops-date-text">
                            {{ $billing->jumlah_jam }} Jam
                        </td>

                        <td class="ops-date-text">
                            @if($billing->jam_mulai)
                                {{ $billing->jam_mulai->format('H:i') }}
                                –
                                {{ $billing->jam_selesai ? $billing->jam_selesai->format('H:i') : '-' }}
                                <br>
                                <span class="ops-text-muted-sm">
                                    {{ $billing->jam_mulai->format('d M Y') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <span class="ops-harga-text">
                                Rp {{ number_format($billing->harga_final, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            <span class="booking-tipe-badge tipe-perjam">
                                {{ strtoupper($billing->metode_bayar) }}
                            </span>
                        </td>

                        <td class="ops-table-username">
                                {{ $billing->createdBy->name ?? '-' }}
                        </td>

                        <td>
                            <div class="ops-aksi-group">
                                <a href="{{ route('operational.billing.show', $billing->id) }}"
                                    class="ops-btn-aksi btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'owner')
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete"
                                    data-id="{{ $billing->id }}" data-code="{{ $billing->transaction_code }}"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="11" class="ops-empty-state">
                            Tidak ada histori billing ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($billings->hasPages())
    <div class="card-footer ops-pagination">
        <nav>
            <ul class="pagination mb-0">

                @if($billings->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $billings->previousPageUrl() }}">&laquo;</a>
                </li>
                @endif

                @foreach($billings->getUrlRange(1, $billings->lastPage()) as $page => $url)
                @if($page == $billings->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
                @endif
                @endforeach

                @if($billings->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $billings->nextPageUrl() }}">&raquo;</a>
                </li>
                @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif

            </ul>
        </nav>
    </div>
    @endif
</div>

@endsection