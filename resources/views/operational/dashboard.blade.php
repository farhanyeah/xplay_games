@extends('layouts.operational')

@section('title', 'Dashboard | XPLAY Games')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <p class="text-muted">Selamat datang, {{ Auth::user()->name }}! 👋</p>

@endsection