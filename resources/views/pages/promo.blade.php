@extends('layouts.main')

@section('title', 'Promo | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/promo.css') }}">
@endpush

@section('content')

<!-- Promo Section -->
<section class="promo-page">

    <div class="container">

        <!-- Title -->
        <div class="promo-title reveal">

            <div class="section-label center-label">
                <p>PROMO</p>
            </div>

            <h1>Promo XPLAY Games</h1>

            <p>
                Nikmati berbagai promo menarik dari XPLAY Games
            </p>
        </div>

        <!-- Wrapper -->
        <div class="promo-wrapper">

            <!-- Promo Happy Hour -->
            <div class="promo-card reveal">

                <div class="promo-image">
                    <img src="{{ asset('images/promo/promo_happy_hour.jpeg') }}" alt="Promo Happy Hour">
                </div>

                <div class="promo-content">
                    <h2>Happy Hour</h2>
                    <ul>
                        <li>Khusus untuk Paket 3 jam</li>
                        <li>Berlaku Senin - Jumat, pukul 10.00 - 16.00</li>
                        <li>Tidak berlaku pada hari libur nasional</li>
                    </ul>
                </div>
            </div>

            <!-- Promo Paket Unlimited -->
            <div class="promo-card reveal">

                <div class="promo-image">
                    <img src="{{ asset('images/promo/promo_unlimited.jpeg') }}" alt="Promo Unlimited">
                </div>

                <div class="promo-content">
                    <h2>Paket Unlimited</h2>
                    <ul>
                        <li>Tersedia pilihan paket pagi dan malam</li>
                        <li>Paket Pagi: 6 jam <br> pukul 10.00 - 16.00</li>
                        <li>Paket Malam: 5 jam <br> pukul 22.00 - 03.00</li>
                    </ul>
                </div>
            </div>

            <!-- Promo Rating Gmaps -->
            <div class="promo-card reveal">

                <div class="promo-image maps-image">
                    <img src="{{ asset('images/promo/promo-gmaps.jpg') }}" alt="Promo Rating Gmaps">
                </div>

                <div class="promo-content">
                    <h2>Ulasan Google Maps</h2>
                    <ul>
                        <li>Dapatkan diskon Rp 2.000 untuk setiap kunjungan</li>
                        <li>Cari XPLAY Games di Google Maps</li>
                        <li>Berikan ulasan positif dan bintang 5</li>
                        <li>Tunjukkan hasil ulasan ke admin</li>
                    </ul>
                </div>
            </div>

            <!-- Promo Birthday Treat -->
            <div class="promo-card reveal">

                <div class="promo-image birthday-image">
                    <img src="{{ asset('images/promo/promo-birthday.jpg') }}" alt="Birthday Treat">
                </div>

                <div class="promo-content">
                    <h2>Birthday Treat</h2>

                    <ul>
                        <li>Klaim hanya bisa dilakukan satu kali per orang</li>
                        <li>Tanggal ulang tahun harus sesuai dengan KTP</li>
                        <li>Bebas pilih rasa Pop Mie favoritmu</li>
                        <li>Wajib bermain minimal 1 jam (unit bebas)</li>
                        <li>Ikuti Instagram, TikTok, dan YouTube XPLAY Games</li>
                    </ul>
                </div>
            </div>
            
        </div>
        
    </div>
</section>

@endsection