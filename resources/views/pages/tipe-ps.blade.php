@extends('layouts.main')

@section('title', 'Tipe PlayStation | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tipe-ps.css') }}">
@endpush

@section('content')

<!-- Tipe PlayStation XPLAY -->
<section class="ps-page">

    <div class="container">

        <!-- Title -->
        <div class="ps-title reveal">

            <div class="section-label center-label">
                <p>PLAYSTATION</p>
            </div>

            <h1>Tipe PlayStation XPLAY</h1>
            <p>
                Pilih tipe PlayStation favoritmu dan nikmati fasilitas 
                lengkap untuk pengalaman gaming terbaik
            </p>
        </div>

        <!-- Wrapper -->
        <div class="ps-wrapper">

            <!-- PS 4 REGULER -->
            <div class="ps-card reveal">

                <div class="ps-main-image">
                    <img src="{{ asset('images/tipe_ps/ps4-main.jpeg') }}" alt="PS 4 Reguler">
                </div>

                <div class="ps-thumbnails">
                    <img src="{{ asset('images/tipe_ps/ps4-1.jpg') }}" alt="PS 4 Reguler - Tampilan 1">
                    <img src="{{ asset('images/tipe_ps/ps4-2.jpg') }}" alt="PS 4 Reguler - Tampilan 2">
                    <img src="{{ asset('images/tipe_ps/ps4-3.jpeg') }}" alt="PS 4 Reguler - Tampilan 3">
                </div>

                <div class="ps-content">
                    <h2>PS 4 Reguler</h2>
                    <ul>
                        <li>Tersedia di lantai 1, Unit 1 - 8</li>
                        <li>Tersedia di lantai 2, Unit 11 - 16</li>
                        <li>Layar TV 32 inch</li>
                        <li>Tersedia 2 controller</li>
                    </ul>
                </div>

            </div>

            <!-- PS 4 PRO -->
            <div class="ps-card reveal">

                <div class="ps-main-image">
                    <img src="{{ asset('images/tipe_ps/ps4pro-main.jpeg') }}" alt="PS 4 Pro">
                </div>

                <div class="ps-thumbnails">
                    <img src="{{ asset('images/tipe_ps/ps4pro-1.jpeg') }}" alt="PS 4 Pro - Tampilan 1">
                    <img src="{{ asset('images/tipe_ps/ps4pro-2.jpeg') }}" alt="PS 4 Pro - Tampilan 2">
                    <img src="{{ asset('images/tipe_ps/ps4pro-3.jpeg') }}" alt="PS 4 Pro - Tampilan 3">
                </div>

                <div class="ps-content">
                    <h2>PS 4 Pro</h2>
                    <ul>
                        <li>Hanya tersedia di lantai 2, Unit 17</li>
                        <li>Layar TV 43 inch</li>
                        <li>Tersedia 2 controller</li>
                        <li>Koleksi game selalu diperbarui lebih awal</li>
                    </ul>
                </div>

            </div>

            <!-- PS 5 REGULER -->
            <div class="ps-card reveal">

                <div class="ps-main-image">
                    <img src="{{ asset('images/tipe_ps/ps5-main.jpeg') }}" alt="PS 5 Reguler">
                </div>

                <div class="ps-thumbnails">
                    <img src="{{ asset('images/tipe_ps/ps5-1.jpg') }}" alt="PS 5 Reguler - Tampilan 1">
                    <img src="{{ asset('images/tipe_ps/ps5-2.jpeg') }}" alt="PS 5 Reguler - Tampilan 2">
                    <img src="{{ asset('images/tipe_ps/ps5-3.jpeg') }}" alt="PS 5 Reguler - Tampilan 3">
                </div>

                <div class="ps-content">
                    <h2>PS 5 Reguler</h2>
                    <ul>
                        <li>Tersedia di lantai 1, Unit 9</li>
                        <li>Tersedia di lantai 2, Unit 18</li>
                        <li>Tersedia 2 controller</li>
                        <li>Game selalu diperbarui ke versi terbaru</li>
                    </ul>
                </div>

            </div>

            <!-- PS 5 VIP -->
            <div class="ps-card reveal">

                <div class="ps-main-image">
                    <img src="{{ asset('images/tipe_ps/ps5vip-main.jpeg') }}" alt="PS 5 VIP">
                </div>

                <div class="ps-thumbnails">
                    <img src="{{ asset('images/tipe_ps/ps5vip-1.jpg') }}" alt="PS 5 VIP - Tampilan 1">
                    <img src="{{ asset('images/tipe_ps/ps5vip-2.jpg') }}" alt="PS 5 VIP - Tampilan 2">
                    <img src="{{ asset('images/tipe_ps/ps5vip-3.jpg') }}" alt="PS 5 VIP - Tampilan 3">
                </div>

                <div class="ps-content">
                    <h2>PS 5 VIP</h2>
                    <ul>
                        <li>Berlokasi di lantai 1 (Smoking Area)</li>
                        <li>Tersedia 2 controller</li>
                        <li>Ruangan kaca dengan AC & sofa</li>
                        <li>Tidak tersedia Netflix</li>
                        <li>Tidak memerlukan eKTP untuk reservasi</li>
                    </ul>
                </div>

            </div>

            <!-- PS 5 VVIP -->
            <div class="ps-card reveal">

                <div class="ps-main-image">
                    <img src="{{ asset('images/tipe_ps/ps5vvip-main.jpg') }}" alt="PS 5 VVIP">
                </div>

                <div class="ps-thumbnails">
                    <img src="{{ asset('images/tipe_ps/ps5vvip-1.jpg') }}" alt="PS 5 VVIP - Tampilan 1">
                    <img src="{{ asset('images/tipe_ps/ps5vvip-2.jpeg') }}" alt="PS 5 VVIP - Tampilan 2">
                    <img src="{{ asset('images/tipe_ps/ps5vvip-3.jpg') }}" alt="PS 5 VVIP - Tampilan 3">
                </div>

                <div class="ps-content">
                    <h2>PS 5 VVIP</h2>
                    <ul>
                        <li>Ruang private di lantai 2 dengan AC tersendiri</li>
                        <li>Dilengkapi dengan Nintendo Switch</li>
                        <li>Tersedia 4 controller, cocok untuk main bareng ramai-ramai</li>
                        <li>Sofa empuk & akses Netflix untuk bersantai</li>
                        <li>Area Non-Smoking yang sejuk (vape diperbolehkan)</li>
                        <li>Wajib menyertakan eKTP untuk reservasi</li>
                    </ul>
                </div>

            </div>

        </div>
        
    </div>
</section>

@endsection