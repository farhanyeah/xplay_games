@extends('layouts.auth')

@section('title', 'Login | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')

<section class="auth-page">

    <div class="auth-container">

        <!-- LEFT SIDE -->
        <div class="auth-left">
            <a href="{{ route('home') }}" class="auth-logo">
                <img src="{{ asset('images/main/xplay_games.jpg') }}" alt="XPLAY Games Logo">
                <span>XPLAY</span>
            </a>

            <div class="auth-left-content">
                <p class="auth-subtitle">WELCOME BACK!</p>
                <h1>
                    Login dan lanjutkan
                    pengalaman gaming terbaikmu.
                </h1>
                <p class="auth-description">
                    Booking, Sewa Unit, dan main — semua dalam satu tempat.
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h2>Login</h2>
                    <p>Masuk ke akun XPLAY Games Anda.</p>
                </div>

                {{-- Notifikasi sukses register --}}
                @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                            required autofocus>
                        @error('email')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <!-- Remember Me -->
                    <div class="auth-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember Me</span>
                        </label>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Forgot Password?
                        </a>
                        @endif
                    </div>

                    <!-- Button -->
                    <button type="submit" class="auth-button">
                        Login
                    </button>

                </form>

                <!-- Bottom -->
                <div class="auth-bottom">
                    <p>
                        Belum punya akun?
                        <a href="{{ route('register') }}">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection
