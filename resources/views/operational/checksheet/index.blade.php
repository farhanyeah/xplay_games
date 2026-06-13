@extends('layouts.operational')

@php
if (!isset($currentShift)) $currentShift = 'pagi';
if (!isset($dailyItems)) $dailyItems = collect();
if (!isset($biweeklyItems)) $biweeklyItems = collect();
if (!isset($monthlyItems)) $monthlyItems = collect();
@endphp

@section('title', 'Checksheet | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/checksheet.css') }}">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Checksheet</h1>

    <div class="checksheet-info">
        <div class="info-item">
            <i class="fas fa-user mr-1"></i>
            <span class="font-weight-bold">{{ Auth::user()->name }}</span>
        </div>
        <div class="info-item">
            <i class="fas fa-clock mr-1"></i>
            <span>Shift {{ ucfirst($currentShift) }}</span>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(isset($existingHeader))
<div class="alert alert-info">
    <i class="fas fa-check-circle mr-2"></i>
    Checksheet shift {{ $existingHeader->shift }} sudah diisi pada tanggal
    <strong>{{ \Carbon\Carbon::parse($existingHeader->date)->format('d M Y') }}</strong>
</div>
@endif

<form method="POST" action="{{ route('operational.checksheet.store') }}">
    @csrf

    <!-- HARIAN -->
    <div class="card ops-card mb-4">
        <div class="card-header ops-card-header">
            <i class="fas fa-calendar-day mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">
                Checksheet Harian - Shift {{ ucfirst($currentShift) }}
            </h6>
        </div>
        <div class="card-body ops-show-card-body">
            @if($dailyItems->isEmpty())
            <p class="ops-empty-state">Belum ada item. Hubungi owner!</p>
            @else
            @foreach($dailyItems as $item)
            <div class="form-check">
                <input type="checkbox" name="items[{{ $item->id }}]" value="done"
                    class="form-check-input"
                    {{ isset($existingDetails[$item->id]) && $existingDetails[$item->id] == 'done' ? 'checked disabled' : '' }}>
                <label for="daily_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <!-- 2 MINGGUAN -->
    <div class="card ops-card mb-4">
        <div class="card-header ops-card-header justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-week mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Checksheet 2 Mingguan</h6>
            </div>
            <span class="ops-badge-total">
                {{ in_array(now()->day, [1, 15]) ? 'Jadwal' : 'Terkunci' }}
            </span>
        </div>
        <div class="card-body ops-show-card-body">
            @if($biweeklyItems->isEmpty())
            <p class="ops-empty-state">Belum ada item.</p>
            @else
            @foreach($biweeklyItems as $item)
            @php $isLocked = !in_array(now()->day, [1, 15]); @endphp
            <div class="form-check" {{ $isLocked ? 'style="opacity:0.5"' : '' }}>
                <input type="checkbox" name="items[{{ $item->id }}]" id="biweekly_{{ $item->id }}" value="done"
                    class="form-check-input" {{ $isLocked ? 'disabled' : '' }}
                    {{ isset($existingDetails[$item->id]) && $existingDetails[$item->id] == 'done' ? 'checked' : '' }}>
                <label for="biweekly_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <!-- BULANAN -->
    <div class="card ops-card mb-4">
        <div class="card-header ops-card-header justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-alt mr-2 ops-header-icon"></i>
                <h6 class="m-0 font-weight-bold ops-header-title">Checksheet Bulanan</h6>
            </div>
            <span class="ops-badge-total">
                {{ now()->day == 1 ? 'Jadwal' : 'Terkunci' }}
            </span>
        </div>
        <div class="card-body ops-show-card-body">
            @if($monthlyItems->isEmpty())
            <p class="ops-empty-state">Belum ada item.</p>
            @else
            @foreach($monthlyItems as $item)
            @php $isLocked = now()->day != 1; @endphp
            <div class="form-check" {{ $isLocked ? 'style="opacity:0.5"' : '' }}>
                <input type="checkbox" name="items[{{ $item->id }}]" id="monthly_{{ $item->id }}" value="done"
                    class="form-check-input" {{ $isLocked ? 'disabled' : '' }}
                    {{ isset($existingDetails[$item->id]) && $existingDetails[$item->id] == 'done' ? 'checked' : '' }}>
                <label for="monthly_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="ops-btn-aksi-full btn-primary btn-simpan-checksheet">
            <i class="fas fa-save mr-1"></i> Simpan Checksheet
        </button>
    </div>
   
</form>

@push('scripts')
<script src="{{ asset('js/operational/checksheet.js') }}"></script>
@endpush

@endsection