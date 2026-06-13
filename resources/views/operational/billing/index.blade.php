@extends('layouts.operational')

@section('title', 'Billing | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<link rel="stylesheet" href="{{ asset('css/operational/billing.css') }}">
@endpush

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">Billing</h1>

    <a href="{{ route('operational.billing.history') }}" class="btn ops-btn-filter">
        <i class="fas fa-history mr-1"></i> Histori Billing
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

@include('operational.billing.floor', [
    'title' => 'Lantai 1',
    'icon' => 'fas fa-layer-group',
    'units' => $lantai1
])

@include('operational.billing.floor', [
    'title' => 'Lantai 2',
    'icon' => 'fas fa-building',
    'units' => $lantai2
])

@include('operational.billing.modal-create')
@include('operational.billing.modal-extend')
@include('operational.billing.modal-refund')
@include('operational.billing.modal-pindah-unit')

@endsection

@push('scripts')

<script>
const csrfToken = '{{ csrf_token() }}';

const getPaketHargaUrl =
    '{{ route("operational.billing.getPaketHarga") }}';
</script>

<script src="{{ asset('js/operational/billing.js') }}"></script>

@endpush