@extends('layouts.operational')

@section('title', 'Data Sewa | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/data-sewa.css') }}">
@endpush

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Data Sewa</h1>

    <div class="pendapatan-hari-ini">
        <div class="pendapatan-label">
            <i class="fas fa-coins mr-1"></i> Pendapatan Sewa Hari Ini
        </div>
        <div class="pendapatan-value">
            Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
        </div>
    </div>
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

<!-- Filter Data Sewa -->
<div class="card ops-card mb-4">

    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>

    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.sewa.index') }}">

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Cari Kode / Nama</label>
                    <input type="text" name="search" class="form-control ops-input"
                        placeholder="Kode transaksi / nama..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Status Sewa</label>
                    <select name="status" class="form-control ops-input">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending
                        </option>
                        <option value="disewa" {{ request('status') == 'disewa'    ? 'selected' : '' }}>Disewa</option>
                        <option value="extended" {{ request('status') == 'extended'  ? 'selected' : '' }}>Extended
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Unit PS</label>
                    <select name="unit_id" class="form-control ops-input">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->kode_unit }} - {{ $unit->jenisUnit->tipe }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal" class="form-control ops-input" 
                    value="{{ request('tanggal') ?? now()->toDateString() }}">
                </div>
            </div>

            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>   
                <a href="{{ route('operational.sewa.index') }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>

<!-- Tabel Data Sewa -->
<div class="card ops-card mb-4">

    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-gamepad mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Transaksi Sewa</h6>
        </div>
        <span class="ops-badge-total">
            Total: {{ $sewas->total() }} transaksi
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Transaksi</th>
                        <th>Customer</th>
                        <th>Unit</th>
                        <th>Durasi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sewas as $index => $sewa)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $sewas->firstItem() + $index }}</td>

                        <td>
                            <span class="ops-transaction-code">{{ $sewa->transaction_code }}</span>
                        </td>

                        <td>
                            <div class="ops-customer-name">{{ $sewa->nama }}</div>
                            <div class="ops-customer-phone">{{ $sewa->no_hp }}</div>
                        </td>

                        <td>
                            <div class="ops-unit-code">{{ $sewa->unitSewa->kode_unit }}</div>
                            <div class="ops-unit-type">{{ $sewa->unitSewa->jenisUnit->tipe }}</div>
                        </td>

                        <td>
                            @if($sewa->paket_id)
                            <span class="durasi_paket">{{ $sewa->paket->durasi_hari }} Hari</span>
                            @else
                            <span class="custom-label">{{ $sewa->durasi_custom }} Hari (Custom)</span>
                            @endif
                        </td>

                        <td class="ops-date-text">
                            {{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->format('d M Y, H:i') }}
                        </td>

                        <td class="ops-date-text">
                            {{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->format('d M Y, H:i') }}
                        </td>

                        <td>
                            <span class="ops-harga-text">
                                Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            @switch($sewa->status_sewa)
                            @case('pending')
                            <span class="ops-status-badge status-pending">Pending</span>
                            @break
                            @case('disewa')
                            <span class="ops-status-badge status-disewa">Disewa</span>
                            @break
                            @case('extended')
                            <span class="ops-status-badge status-extended">Extended</span>
                            @break
                            @case('completed')
                            <span class="ops-status-badge status-completed">Selesai</span>
                            @break
                            @case('cancelled')
                            <span class="ops-status-badge status-cancelled">Dibatalkan</span>
                            @break
                            @endswitch
                        </td>

                        <td>
                            <div class="ops-aksi-group">
                                <a href="{{ route('operational.sewa.show', $sewa->id) }}"
                                    class="ops-btn-aksi btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'owner')
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete"
                                    data-id="{{ $sewa->id }}" 
                                    data-code="{{ $sewa->transaction_code }}" 
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="10" class="ops-empty-state">
                            Tidak ada data sewa ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($sewas->hasPages())
    <div class="card-footer ops-pagination justify-content-center">
        <nav>
            <ul class="pagination mb-0">

                {{-- Previous --}}
                @if($sewas->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $sewas->previousPageUrl() }}">&laquo;</a>
                </li>
                @endif

                {{-- Nomor Halaman --}}
                @foreach($sewas->getUrlRange(1, $sewas->lastPage()) as $page => $url)
                @if($page == $sewas->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
                @endif
                @endforeach

                {{-- Next --}}
                @if($sewas->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $sewas->nextPageUrl() }}">&raquo;</a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
                @endif

            </ul>
        </nav>
    </div>
    @endif

</div>

<!-- Form Delete Hidden -->
<form id="deleteSewa" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const id = this.dataset.id;
                const code = this.getAttribute('data-code') || 'Data ini';

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Data Sewa?',
                    text: `Transaksi ${code} akan dihapus permanen!`,
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8'
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteSewa');
                        form.action = `/operational/sewa/${id}`;
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush