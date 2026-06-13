@extends('layouts.operational')

@section('title', 'Data Booking | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/data-booking.css') }}">
@endpush

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Data Booking</h1>

    <div class="pendapatan-hari-ini">
        <div class="pendapatan-label">
            <i class="fas fa-coins mr-1"></i> Pendapatan Booking Hari Ini
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

<!-- Filter -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.booking.index') }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Cari Kode / Nama</label>
                    <input type="text" name="search" class="form-control ops-input"
                        placeholder="Kode transaksi / nama..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">Status Booking</label>
                    <select name="status" class="form-control ops-input">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="booked" {{ request('status') == 'booked'  ? 'selected' : '' }}>Booked</option>
                        <option value="done" {{ request('status') == 'done'    ? 'selected' : '' }}>Selesai</option>
                         <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option></select>
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
                    <label class="ops-filter-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control ops-input" 
                    value="{{ request('tanggal') ?? now()->toDateString() }}">
                </div>

            </div>

            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.booking.index') }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Booking -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-calendar-check mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Transaksi Booking</h6>
        </div>
        <span class="ops-badge-total">
            Total: {{ $bookings->total() }} transaksi
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
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Tipe</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $bookings->firstItem() + $index }}</td>

                        <td>
                            <span class="ops-transaction-code">{{ $booking->transaction_code }}</span>
                        </td>

                        <td>
                            <div class="ops-customer-name">{{ $booking->nama }}</div>
                            <div class="ops-customer-phone">{{ $booking->no_hp }}</div>
                        </td>

                        <td>
                            <div class="ops-unit-code">{{ $booking->unit->kode_unit }}</div>
                            <div class="ops-unit-type">{{ $booking->unit->jenisUnit->tipe }}</div>
                        </td>

                        <td class="ops-date-text">
                            {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                        </td>

                        <td class="ops-date-text">
                            {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}
                            –
                            {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                        </td>

                        <td>
                            @if($booking->paket_id)
                            <span class="booking-tipe-badge tipe-perjam">Per Jam</span>
                            @else
                            <span class="booking-tipe-badge tipe-khusus">{{ $booking->paketKhusus->nama_paket }}</span>
                            @endif
                        </td>

                        <td>
                            <span class="ops-harga-text">
                                Rp {{ number_format($booking->harga, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            @switch($booking->status_booking)
                            @case('pending')
                            <span class="ops-status-badge status-pending">Pending</span>
                            @break
                            @case('booked')
                            <span class="ops-status-badge status-booked">Booked</span>
                            @break
                            @case('done')
                            <span class="ops-status-badge status-done">Selesai</span>
                            @break
                            @case('cancelled')
                            <span class="ops-status-badge status-cancelled">Dibatalkan</span>
                            @break
                            @endswitch
                        </td>

                        <td>
                            <div class="ops-aksi-group">
                                <a href="{{ route('operational.booking.show', $booking->id) }}"
                                    class="ops-btn-aksi btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'owner')
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete"
                                    data-id="{{ $booking->id }}" data-code="{{ $booking->transaction_code }}"
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
                            Tidak ada data booking ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($bookings->hasPages())
    <div class="card-footer ops-pagination">
        <nav>
            <ul class="pagination mb-0">

                @if($bookings->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $bookings->previousPageUrl() }}">&laquo;</a></li>
                @endif

                @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                @if($page == $bookings->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach

                @if($bookings->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $bookings->nextPageUrl() }}">&raquo;</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif

            </ul>
        </nav>
    </div>
    @endif
</div>

<!-- Form Delete Hidden -->
<form id="deleteBooking" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const id = this.dataset.id;
                const code = this.getAttribute('data-code') || 'Data ini';

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Data Booking?',
                    text: `Transaksi ${code} akan dihapus permanen!`,
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8'
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteBooking');
                        form.action = `/operational/booking/${id}`;
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush