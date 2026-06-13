@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('title', 'Kelola Harga | XPLAY Games')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Kelola Harga</h1>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<!-- Tab Navigation -->
<ul class="nav nav-tabs ops-tabs mb-4" id="hargaTabs">
    <li class="nav-item">
        <a class="nav-link {{ request('tab', 'sewa') === 'sewa' ? 'active' : '' }}"
            href="{{ route('operational.kelola-harga', ['tab' => 'sewa']) }}">
            <i class="fas fa-gamepad mr-1"></i> Harga Sewa PS
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('tab') === 'booking' ? 'active' : '' }}"
            href="{{ route('operational.kelola-harga', ['tab' => 'booking']) }}">
            <i class="fas fa-clock mr-1"></i> Harga PS Per Jam
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('tab') === 'khusus' ? 'active' : '' }}"
            href="{{ route('operational.kelola-harga', ['tab' => 'khusus']) }}">
            <i class="fas fa-star mr-1"></i> Paket
        </a>
    </li>
</ul>

{{-- ======================================= --}}
{{-- TAB: HARGA SEWA PS --}}
{{-- ======================================= --}}
@if(request('tab', 'sewa') === 'sewa')

<!-- Filter Tab Sewa -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.kelola-harga') }}">
            <input type="hidden" name="tab" value="sewa">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Tipe PS</label>
                    <select name="filter_jenis_sewa" class="form-control ops-input">
                        <option value="">Semua Tipe</option>
                        @foreach($jenisUnitSewa as $jenis)
                        <option value="{{ $jenis->id }}"
                            {{ request('filter_jenis_sewa') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->tipe }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.kelola-harga', ['tab' => 'sewa']) }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahSewaModal">
        <i class="fas fa-plus mr-1"></i> Tambah Paket Sewa
    </button>
</div>

<div class="card ops-card">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-gamepad mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Harga Sewa PS</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $paketSewa->count() }} paket</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Tipe PS</th>
                        <th width="130">Durasi</th>
                        <th width="150">Harga</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketSewa as $index => $paket)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                        <td><span class="ops-table-item-name">{{ $paket->jenisUnit->tipe }}</span></td>
                        <td><span class="ops-table-item-name">{{ $paket->durasi_hari }} Hari</span></td>
                        <td><span class="ops-harga-text">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span></td>
                        <td>
                            <div class="ops-aksi-group">
                                <button type="button" class="ops-btn-aksi btn-detail btn-edit-sewa"
                                    data-id="{{ $paket->id }}" data-jenis="{{ $paket->jenis_unit_id }}"
                                    data-durasi="{{ $paket->durasi_hari }}" data-harga="{{ $paket->harga }}"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-sewa"
                                    data-id="{{ $paket->id }}"
                                    data-nama="{{ $paket->jenisUnit->tipe }} - {{ $paket->durasi_hari }} Hari"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="ops-empty-state">Belum ada paket harga sewa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Sewa -->
<div class="modal fade ops-modal" id="tambahSewaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="tambahSewaForm" action="{{ route('operational.kelola-harga.sewa.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket Harga Sewa</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Tipe PS <span class="text-danger">*</span></label>
                        <select name="jenis_unit_id" id="tambahSewaJenis" class="form-control ops-input">
                            <option value="">Pilih Tipe PS</option>
                            @foreach($jenisUnitSewa as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->tipe }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="tambahSewaJenis-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Durasi (Hari) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi_hari" id="tambahSewaDurasi" class="form-control ops-input"
                            min="1">
                        <small class="text-danger d-none" id="tambahSewaDurasi-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="tambahSewaHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="tambahSewaHarga-error"></small>
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

<!-- Modal Edit Sewa -->
<div class="modal fade ops-modal" id="editSewaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editSewaForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Paket Harga Sewa</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Tipe PS <span class="text-danger">*</span></label>
                        <select name="jenis_unit_id" id="editSewaJenis" class="form-control ops-input">
                            <option value="">Pilih Tipe PS</option>
                            @foreach($jenisUnitSewa as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->tipe }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="editSewaJenis-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Durasi (Hari) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi_hari" id="editSewaDurasi" class="form-control ops-input"
                            min="1">
                        <small class="text-danger d-none" id="editSewaDurasi-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="editSewaHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="editSewaHarga-error"></small>
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

<form id="deleteSewaForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endif

{{-- ======================================= --}}
{{-- TAB: HARGA BOOKING PER JAM --}}
{{-- ======================================= --}}
@if(request('tab') === 'booking')

<!-- Filter Tab Booking -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.kelola-harga') }}">
            <input type="hidden" name="tab" value="booking">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Tipe PS</label>
                    <select name="filter_jenis_booking" class="form-control ops-input">
                        <option value="">Semua Tipe</option>
                        @foreach($jenisUnitBooking as $jenis)
                        <option value="{{ $jenis->id }}"
                            {{ request('filter_jenis_booking') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->tipe }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.kelola-harga', ['tab' => 'booking']) }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahBookingModal">
        <i class="fas fa-plus mr-1"></i> Tambah Paket Booking
    </button>
</div>

<div class="card ops-card">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-clock mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Harga Booking Per Jam</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $paketBooking->count() }} paket</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Tipe PS</th>
                        <th width="130">Jumlah Jam</th>
                        <th width="150">Harga</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketBooking as $index => $paket)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                        <td><span class="ops-table-item-name">{{ $paket->jenisUnit->tipe }}</span></td>
                        <td><span class="ops-table-item-name">{{ $paket->jumlah_jam }} Jam</span></td>
                        <td><span class="ops-harga-text">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span></td>
                        <td>
                            <div class="ops-aksi-group">
                                <button type="button" class="ops-btn-aksi btn-detail btn-edit-booking"
                                    data-id="{{ $paket->id }}" data-jenis="{{ $paket->jenis_unit_id }}"
                                    data-jam="{{ $paket->jumlah_jam }}" data-harga="{{ $paket->harga }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-booking"
                                    data-id="{{ $paket->id }}"
                                    data-nama="{{ $paket->jenisUnit->tipe }} - {{ $paket->jumlah_jam }} Jam"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="ops-empty-state">Belum ada paket harga booking</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Booking -->
<div class="modal fade ops-modal" id="tambahBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id=tambahBookingForm action="{{ route('operational.kelola-harga.booking.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket Harga Booking</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Tipe PS <span class="text-danger">*</span></label>
                        <select name="jenis_unit_id" id="tambahBookingJenis" class="form-control ops-input">
                            <option value="">Pilih Tipe PS</option>
                            @foreach($jenisUnitBooking as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->tipe }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="tambahBookingJenis-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jumlah Jam <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_jam" id="tambahBookingJam" class="form-control ops-input"
                            min="1">
                        <small class="text-danger d-none" id="tambahBookingJam-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="tambahBookingHarga" class="form-control ops-input"
                            min="0">
                        <small class="text-danger d-none" id="tambahBookingHarga-error"></small>
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

<!-- Modal Edit Booking -->
<div class="modal fade ops-modal" id="editBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editBookingForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Paket Harga Booking</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Tipe PS <span class="text-danger">*</span></label>
                        <select name="jenis_unit_id" id="editBookingJenis" class="form-control ops-input">
                            <option value="">Pilih Tipe PS</option>
                            @foreach($jenisUnitBooking as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->tipe }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="editBookingJenis-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jumlah Jam <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_jam" id="editBookingJam" class="form-control ops-input"
                            min="1">
                        <small class="text-danger d-none" id="editBookingJam-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="editBookingHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="editBookingHarga-error"></small>
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

<form id="deleteBookingForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endif

{{-- ======================================= --}}
{{-- TAB: PAKET KHUSUS BOOKING --}}
{{-- ======================================= --}}
@if(request('tab') === 'khusus')

<!-- Filter Tab Khusus -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-filter mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Filter Data</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.kelola-harga') }}">
            <input type="hidden" name="tab" value="khusus">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Tipe PS</label>
                    <select name="filter_jenis_khusus" class="form-control ops-input">
                        <option value="">Semua Tipe</option>
                        @foreach($jenisUnitBooking as $jenis)
                        <option value="{{ $jenis->id }}"
                            {{ request('filter_jenis_khusus') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->tipe }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Nama Paket</label>
                    <select name="filter_nama_khusus" class="form-control ops-input">
                        <option value="">Semua Nama Paket</option>

                        @foreach($namaPaketKhusus as $nama_paket)
                        <option value="{{ $nama_paket->nama_paket }}"
                            {{ request('filter_nama_khusus') == $nama_paket->nama_paket ? 'selected' : '' }}>
                            {{ $nama_paket->nama_paket }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn ops-btn-filter mr-2">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('operational.kelola-harga', ['tab' => 'khusus']) }}" class="btn ops-btn-reset">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="ops-btn-tambah" data-toggle="modal" data-target="#tambahKhususModal">
        <i class="fas fa-plus mr-1"></i> Tambah Paket Khusus
    </button>
</div>

<div class="card ops-card">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-star mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Daftar Paket Khusus Booking</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $paketKhusus->count() }} paket</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Paket</th>
                        <th>Tipe PS</th>
                        <th width="100">Jam</th>
                        <th width="150">Harga</th>
                        <th>Hari Berlaku</th>
                        <th width="130">Jam Berlaku</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paketKhusus as $index => $paket)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $index + 1 }}</td>
                        <td><span class="ops-table-item-name">{{ $paket->nama_paket }}</span></td>
                        <td><span class="ops-table-item-name">{{ $paket->jenisUnit->tipe }}</span></td>
                        <td><span class="ops-table-item-name">{{ $paket->jumlah_jam }} Jam</span></td>
                        <td><span class="ops-harga-text">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span></td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @php
$hariMap = ['1'=>'Senin','2'=>'Selasa','3'=>'Rabu','4'=>'Kamis','5'=>'Jumat','6'=>'Sabtu','7'=>'Minggu'];
@endphp
@foreach($paket->hari_berlaku ?? [] as $hari)
<span class="ops-status-badge status-disewa">
    {{ isset($hariMap[$hari]) ? $hariMap[$hari] : ucfirst($hari) }}
</span>
@endforeach
                            </div>
                        </td>
                        <td>
                            <span class="ops-date-text">
                                {{ \Carbon\Carbon::parse($paket->jam_mulai_berlaku)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($paket->jam_selesai_berlaku)->format('H:i') }}
                            </span>
                        </td>
                        <td>
                            <div class="ops-aksi-group">
                                <button type="button" class="ops-btn-aksi btn-detail btn-edit-khusus"
                                    data-id="{{ $paket->id }}" data-jenis="{{ $paket->jenis_unit_id }}"
                                    data-nama="{{ $paket->nama_paket }}" data-jam="{{ $paket->jumlah_jam }}"
                                    data-harga="{{ $paket->harga }}" data-hari="{{ json_encode($paket->hari_berlaku) }}"
                                    data-mulai="{{ $paket->jam_mulai_berlaku }}"
                                    data-selesai="{{ $paket->jam_selesai_berlaku }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-khusus"
                                    data-id="{{ $paket->id }}" data-nama="{{ $paket->nama_paket }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="ops-empty-state">Belum ada paket khusus booking</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Khusus -->
<div class="modal fade ops-modal" id="tambahKhususModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="tambahKhususForm" action="{{ route('operational.kelola-harga.khusus.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket Khusus Booking</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" id="tambahKhususNama" class="form-control ops-input">
                        <small class="text-danger d-none" id="tambahKhususNama-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Tipe PS <span class="text-danger">*</span></label>
                        <select name="jenis_unit_id" id="tambahKhususJenis" class="form-control ops-input">
                            <option value="">Pilih Tipe PS</option>
                            @foreach($jenisUnitBooking as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->tipe }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="tambahKhususJenis-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jumlah Jam <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_jam" id="tambahKhususJam" class="form-control ops-input"
                            min="1">
                        <small class="text-danger d-none" id="tambahKhususJam-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="tambahKhususHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="tambahKhususHarga-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Hari Berlaku <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $hari)
                            <div class="form-check">
                                <input type="checkbox" name="hari_berlaku[]" value="{{ $hari }}" id="tambah_{{ $hari }}"
                                    class="form-check-input">
                                <label for="tambah_{{ $hari }}"
                                    class="ops-filter-label mb-0">{{ ucfirst($hari) }}</label>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-danger d-none" id="tambahKhususHari-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jam Mulai Berlaku <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai_berlaku" id="tambahKhususMulai"
                            class="form-control ops-input">
                        <small class="text-danger d-none" id="tambahKhususMulai-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jam Selesai Berlaku <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai_berlaku" id="tambahKhususSelesai"
                            class="form-control ops-input">
                        <small class="text-danger d-none" id="tambahKhususSelesai-error"></small>
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

<!-- Modal Edit Khusus -->
<div class="modal fade ops-modal" id="editKhususModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editKhususForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Paket Khusus Booking</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" id="editKhususNama" class="form-control ops-input">
                        <small class="text-danger d-none" id="editKhususNama-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Tipe PS <span class="text-danger">*</span></label>
                        <select name="jenis_unit_id" id="editKhususJenis" class="form-control ops-input">
                            <option value="">Pilih Tipe PS</option>
                            @foreach($jenisUnitBooking as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->tipe }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="editKhususJenis-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jumlah Jam <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_jam" id="editKhususJam" class="form-control ops-input"
                            min="1">
                        <small class="text-danger d-none" id="editKhususJam-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="editKhususHarga" class="form-control ops-input" min="0">
                        <small class="text-danger d-none" id="editKhususHarga-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Hari Berlaku <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $hari)
                            <div class="form-check">
                                <input type="checkbox" name="hari_berlaku[]" value="{{ $hari }}" id="edit_{{ $hari }}"
                                    class="form-check-input edit-hari-checkbox">
                                <label for="edit_{{ $hari }}" class="ops-filter-label mb-0">{{ ucfirst($hari) }}</label>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-danger d-none" id="editKhususHari-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jam Mulai Berlaku <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai_berlaku" id="editKhususMulai" class="form-control ops-input">
                        <small class="text-danger d-none" id="editKhususMulai-error"></small>
                    </div>
                    <div class="form-group">
                        <label class="ops-filter-label">Jam Selesai Berlaku <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai_berlaku" id="editKhususSelesai"
                            class="form-control ops-input">
                        <small class="text-danger d-none" id="editKhususSelesai-error"></small>
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

<form id="deleteKhususForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endif

@push('scripts')
<script src="{{ asset('js/operational/kelola-harga.js') }}"></script>
@endpush

@endsection