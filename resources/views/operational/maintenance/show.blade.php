@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
@endpush

@section('title', 'Detail Maintenance | XPLAY Games')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Detail Maintenance</h1>

    <a href="{{ route('operational.maintenance.index') }}" class="ops-btn-back">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
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

<!-- Main Card -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-tools mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Detail Maintenance</h6>
    </div>
    <div class="card-body ops-show-card-body">

        <!-- Status -->
        <div class="mb-3">
            @switch($maintenance->status)
                @case('open')
                <span class="ops-status-badge status-pending">Open</span>
                @break
                @case('resolved')
                <span class="ops-status-badge status-done">Resolved</span>
                @break
            @endswitch
        </div>

        <!-- Info Table -->
        <table class="ops-info-table">
            <tr>
                <td class="ops-info-label">Judul</td>
                <td class="ops-info-value">{{ $maintenance->title }}</td>
            </tr>

            <tr>
                <td class="ops-info-label">Deskripsi</td>
                <td class="ops-info-value">
                    @if(auth()->user()->role == 'staf' && $maintenance->status == 'open')
                    <!-- VIEW MODE -->
                    <div id="deskripsi-view">
                        <span style="white-space: pre-line">{{ $maintenance->description ?? '-' }}</span>
                        <button type="button" class="btn btn-sm" onclick="toggleEditDeskripsi()" 
                                style="background: none; border: none; color: #6366f1; padding: 0; margin-left: 4px;
                                vertical-align: middle;">
                            <i class="fas fa-pencil-alt" style="font-size: 11px;"></i>
                        </button>
                    </div>

                    <!-- EDIT MODE (hidden) -->
                    <div id="deskripsi-edit" style="display: none;">
                        <form method="POST" action="{{ route('operational.maintenance.updateDescription', $maintenance->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="d-flex gap-2" style="gap: 8px;">
                                <textarea name="description" 
                                          class="form-control ops-input" 
                                          rows="3"
                                          style="flex: 1;">{{ $maintenance->description }}</textarea>
                                <div class="d-flex flex-column" style="gap: 6px;">
                                    <button type="submit" class="btn btn-sm" 
                                            style="background: #22c55e; color: white; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-check" style="font-size: 11px;"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm" 
                                            style="background: #64748b; color: white; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center;"
                                            onclick="toggleEditDeskripsi()">
                                        <i class="fas fa-times" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                    {{ $maintenance->description ?? '-' }}
                    @endif
                </td>
            </tr>

            <tr>
                <td class="ops-info-label">Dibuat Oleh</td>
                <td class="ops-info-value">{{ $maintenance->creator->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="ops-info-label">Dibuat Tanggal</td>
                <td class="ops-info-value">{{ $maintenance->created_at->format('d M Y H:i') }}</td>
            </tr>
            @if($maintenance->resolved_at)
            <tr>
                <td class="ops-info-label">Resolved</td>
                <td class="ops-info-value">
                    @if($maintenance->resolved_at)
                    {{ \Carbon\Carbon::parse($maintenance->resolved_at)->format('d M Y H:i') }}
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endif
        </table>
    </div>
</div>

<!-- Photos -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-images mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Foto</h6>
    </div>
    <div class="card-body ops-show-card-body">
        @if($maintenance->photos->count())
            <div class="row">
                @foreach($maintenance->photos as $photo)
                <div class="col-md-3 mb-3">
                    <img src="{{ asset('storage/'.$photo->file_path) }}"
                        class="img-fluid rounded"
                        style="border: 1px solid #e2e8f0; cursor: pointer;"
                        alt="Maintenance Photo"
                        onclick="window.open('{{ asset('storage/'.$photo->file_path) }}', '_blank')">
                </div>
                @endforeach
            </div>
        @else
            <p class="ops-empty-state">Tidak ada foto</p>
        @endif
    </div>
</div>

<!-- Feedback -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-comment-dots mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Owner Feedback</h6>
    </div>
    <div class="card-body ops-show-card-body">
        @if($maintenance->feedback)
            <div class="p-3" style="background: #f1f5f9; border-radius: 8px;">
                <p class="mb-2">{{ $maintenance->feedback->feedback }}</p>
                <small class="text-muted">
                    <i class="fas fa-user mr-1"></i>{{ $maintenance->feedback->creator->name ?? 'Unknown' }}
                    <span class="mx-2">|</span>
                    <i class="fas fa-clock mr-1"></i>{{ $maintenance->feedback->created_at->format('d M Y H:i') }}
                </small>
            </div>
        @else
            <p class="ops-empty-state">Belum ada feedback</p>
        @endif
    </div>
</div>

<!-- Feedback Form - Owner Only -->
@if(auth()->user()->role == 'owner' && $maintenance->status == 'open' && !$maintenance->feedback)
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-paper-plane mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Kirim Feedback</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="POST" action="{{ route('operational.maintenance.feedback', $maintenance->id) }}">
            @csrf
            <div class="form-group mb-3">
                <label class="ops-filter-label">Feedback</label>
                <textarea name="feedback" 
                          class="form-control ops-input" 
                          rows="4" 
                          style="min-height: 100px; height: auto;"
                          placeholder="Tulis feedback untuk maintenance ini..."></textarea>
                @error('feedback')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="ops-btn-aksi-full btn-tambah">
                <i class="fas fa-paper-plane mr-1"></i> Kirim Feedback
            </button>
        </form>
    </div>
</div>
@endif

<!-- Button Resolve - outside card -->
@if(auth()->user()->role == 'staf' && $maintenance->status == 'open')
<form method="POST" action="{{ route('operational.maintenance.resolve', $maintenance->id) }}">
    @csrf
    <div class="text-right">
        <button type="submit" class="ops-btn-aksi-full btn-complete">
            <i class="fas fa-check-circle mr-1"></i> Tandai Selesai
        </button>
    </div>
</form>
@endif

@push('scripts')
<script>
function toggleEditDeskripsi() {
    const viewMode = document.getElementById('deskripsi-view');
    const editMode = document.getElementById('deskripsi-edit');
    
    if (viewMode.style.display !== 'none') {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    } else {
        viewMode.style.display = 'flex';
        editMode.style.display = 'none';
    }
}
</script>
@endpush

@endsection