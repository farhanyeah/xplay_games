@extends('layouts.auth')

@section('title', 'Register | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')

<section class="auth-page">

    <div class="auth-container register-layout">
        <!-- LEFT SIDE -->
        <div class="auth-left">
            <a href="{{ route('home') }}" class="auth-logo">
                <img src="{{ asset('images/main/xplay.png') }}" alt="XPLAY Games Logo">
                <span>XPLAY</span>
            </a>

            <div class="auth-left-content">
                <p class="auth-subtitle">JOIN XPLAY GAMES!</p>
                <h1>
                    Buat akun dan mulai
                    pengalaman gaming di sini.
                </h1>
                <p class="auth-description">
                    Nikmati fitur booking online, cek slot real-time, 
                    dan berbagai promo menarik — semua untukmu.
                </p>
            </div>  
        </div>

        <!-- RIGHT SIDE -->
        <div class="auth-right">
            <div class="auth-card"> 
                <div class="auth-card-header">
                    <h2>Create Account</h2>
                    <p>Daftarkan akun anda di sini.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                        placeholder="Enter your full name"
                            required autofocus>

                        @error('name')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                        placeholder="Enter your email"
                            required>

                        @error('email')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Create password" 
                        required>

                        @error('password')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" 
                        placeholder="Confirm password" required>
                    </div>

                    <!-- Button -->
                    <button type="submit" class="auth-button">
                        Create Account
                    </button>
                </form>

                <!-- Bottom -->
                <div class="auth-bottom">
                    <p>
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
