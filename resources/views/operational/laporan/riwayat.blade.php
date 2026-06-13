@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('title', 'Riwayat Laporan | XPLAY Games')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">
        <i></i>Riwayat Laporan
    </h1>
    
    <a href="{{ route('operational.laporan') }}" class="btn ops-btn-reset">
        <i class="fas fa-arrow-left mr-1"></i>Kembali
    </a>
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
        <h6 class="m-0 font-weight-bold ops-header-title">Filter</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.laporan.riwayat') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="ops-filter-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control ops-input" value="{{ $tanggal }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="ops-filter-label">&nbsp;</label>
                    <button type="submit" class="btn ops-btn-filter mr-2">
                        <i class="fas fa-search mr-1"></i>Cari
                    </button>
                    <a href="{{ route('operational.laporan.riwayat') }}" class="btn ops-btn-reset">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card ops-card">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-list-alt mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">Data Laporan</h6>
        </div>
        <span class="ops-badge-total">Total: {{ $laporans->total() }} laporan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table ops-table mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Tanggal</th>
                        <th>Pendapatan</th>
                        <th>Pengeluaran</th>
                        <th>Tutup Kas</th>
                        <th>Petugas</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $index => $laporan)
                    <tr>
                        <td class="ops-text-muted-sm">{{ $laporans->firstItem() + $index }}</td>
                        <td class="ops-date-text">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td>
                        <td><span class="ops-harga-text text-success">+Rp {{ number_format($laporan->total_pendapatan, 0, ',', '.') }}</span></td>
                        <td><span class="ops-harga-text text-danger">-Rp {{ number_format($laporan->pengeluaran_part_time + $laporan->pengeluaran_gestun + $laporan->pengeluaran_lain, 0, ',', '.') }}</span></td>
                        <td><span class="ops-harga-text">Rp {{ number_format($laporan->tutup_kas, 0, ',', '.') }}</span></td>
                        <td><span class="ops-table-username">{{ $laporan->createdBy->name ?? '-' }}</span></td>
                        <td>
                            <div class="ops-aksi-group">
                                <a href="{{ route('operational.laporan.exportPdf', $laporan->tanggal) }}" class="ops-btn-aksi btn-detail" target="_blank" title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                
                                @if(Auth::user()->role === 'owner')
                                <button type="button" class="ops-btn-aksi btn-hapus btn-delete-laporan" data-id="{{ $laporan->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="ops-empty-state">Belum ada laporan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($laporans->hasPages())
    <div class="card-footer ops-pagination">
        <nav>
            <ul class="pagination mb-0">
                @if($laporans->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $laporans->previousPageUrl() }}">&laquo;</a></li>
                @endif
                @foreach($laporans->getUrlRange(1, $laporans->lastPage()) as $page => $url)
                @if($page == $laporans->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach
                @if($laporans->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $laporans->nextPageUrl() }}">&raquo;</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>

@if(Auth::user()->role === 'owner')
<form id="deleteLaporanForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endif

@endsection

@push('scripts')
@if(Auth::user()->role === 'owner')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete-laporan');
    const form = document.getElementById('deleteLaporanForm');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Laporan?',
                text: 'Laporan akan dihapus dan bisa di-generate ulang.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8'
            }).then(result => {
                if (result.isConfirmed) {
                    form.action = '/operational/laporan/' + id;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endif
@endpush