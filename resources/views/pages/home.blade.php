@extends('layouts.main')

@section('title', 'Beranda | XPLAY Games')

@section('content')

@if(session('login'))
<div class="alert-bottom-left">
    <i class="fa-solid fa-circle-check"></i>
    {{ session('login') }}
</div>
@endif

<!-- Hero Section -->
<section class="hero" id="beranda">

    <!-- Hero Background Image -->
    <img src="{{ asset('images/beranda/hero.jpg') }}" alt="XPLAY Games Hero" class="hero-bg">

    <div class="hero-overlay"></div>

    <div class="container hero-wrapper">

        <!-- Text -->
        <div class="hero-text">
            <h2>Selamat Datang XGamers!</h2>
            <p class="tagline">"Level Up Laughs, Level Down Stress"</p>
            <p>
                Tempat terbaik untuk menikmati pengalaman bermain PlayStation bersama teman-temanmu
                Cek ketersediaan ruangan, lakukan booking online,
                dan main tanpa ribet hanya di <br>
                XPLAY Games
            </p>
            <a href="#tentang" class="btn-primary">Lihat Selengkapnya</a>
        </div>

    </div>
</section>

<!-- Tentang Kami Section -->
<section class="about reveal" id="tentang">

    <div class="container about-wrapper">

        <!-- Left Side Images -->
        <div class="about-image">
            <img src="{{ asset('images/beranda/about-xplay.jpg') }}" alt="XPLAY Games">
        </div>

        <!-- Right Side -->
        <div class="about-text">

            <!-- Small Title -->
            <div class="section-label">
                <span></span>
                <p>TENTANG KAMI</p>
            </div>

            <!-- Main Title -->
            <h2>XPLAY GAMES</h2>

            <!-- Description -->
            <p>
                XPLAY Games adalah tempat hiburan gaming modern dengan layanan
                rental PlayStation dengan suasana nyaman dan fasilitas lengkap.
            </p>
            <p>
                Kami hadir untuk menemani semua kalangan, mulai dari gamer casual hingga
                komunitas esports lokal dengan pengalaman bermain yang seru dan tak terlupakan.
            </p>
            <p>
                Booking online, informasi real-time, dan main tanpa
                ribet — semua ada di XPLAY Games!
            </p>
        </div>

    </div>
</section>

<!-- Fasilitas Section -->
<section class="facilities reveal" id="fasilitas">

    <div class="container">

        <div class="facilities-wrapper">

            <!-- LEFT -->
            <div class="console-section">
                <div class="section-title">
                    <h2>Konsol Tersedia</h2>
                </div>

                <!-- Card -->
                <div class="console-card">
                    <img src="{{ asset('images/beranda/ps4.jpg') }}" alt="PS4">
                    <div class="console-overlay">
                        <h3>PlayStation 4</h3>
                    </div>
                </div>

                <div class="console-card">
                    <img src="{{ asset('images/beranda/ps4pro.jpg') }}" alt="PS4 Pro">
                    <div class="console-overlay">
                        <h3>PlayStation 4 Pro</h3>
                    </div>
                </div>

                <div class="console-card">
                    <img src="{{ asset('images/beranda/ps-5.jpg') }}" alt="PS5">
                    <div class="console-overlay">
                        <h3>PlayStation 5</h3>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="facility-section">

                <div class="section-title">
                    <h2>Fasilitas Lainnya</h2>
                </div>

                <div class="facility-grid">
                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/snack.jpg') }}" alt="Makanan Ringan">
                        <p>Makanan Ringan</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/drink.jpg') }}" alt="Minuman">
                        <p>Minuman</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/wifi.png') }}" alt="WiFi">
                        <p>WiFi</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/no_smoking.png') }}" alt="Area Bebas Rokok">
                        <p>Area Bebas Rokok</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/netflix.png') }}" alt="Netflix">
                        <p>Netflix</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/nintendo.png') }}" alt="Nintendo">
                        <p>Nintendo</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/mushola.png') }}" alt="Mushola">
                        <p>Mushola</p>
                    </div>

                    <div class="facility-card">
                        <img src="{{ asset('images/beranda/toilet.png') }}" alt="Toilet">
                        <p>Toilet</p>
                    </div>

                </div>
            </div>
            
        </div>
        
    </div>
</section>

<!-- Cek Ketersediaan Section -->
<section class="check-slot reveal" id="cek-slot">

    <div class="container">

        <div class="check-wrapper">

            <!-- Left -->
            <div class="check-title">
                <h2>
                    Cek <br>
                    Ketersediaan <br>
                    Ruangan di Sini!
                </h2>
            </div>

            <!-- Right -->
            <div class="check-description">
                <p>
                    Lihat ketersediaan ruangan dan
                    PlayStation secara real-time — kapan saja, di mana saja.
                    Booking jadi lebih praktis tanpa perlu datang langsung!
                </p>
            </div>

        </div>

        <!-- Button -->
        <div class="check-button">
            <a href="{{ route('cek.slot') }}" class="btn-check-slot">
                Cek Ketersediaan
            </a>
        </div>

    </div>
</section>

<!-- Daftar Harga Section -->
<section class="pricelist reveal" id="pricelist">

    <div class="container">

        <!-- Title -->
        <div class="pricelist-title">
            <h2>Daftar Harga</h2>
        </div>

        <!-- Wrapper -->
        <div class="pricelist-wrapper">

            <!-- Left Card -->
            <div class="price-card">
                <h3>Main di Tempat</h3>
                <img src="{{ asset('images/beranda/new-pricelist.jpeg') }}" alt="PlayStation">
            </div>

            <!-- Divider -->
            <div class="price-divider"></div>

            <!-- Right Card -->
            <div class="price-card">
                <h3>Sewa PlayStation</h3>
                <img src="{{ asset('images/beranda/sewa_ps.jpeg') }}" alt="Sewa PlayStation">
            </div>

        </div>

    </div>
</section>

<!-- Game Section -->
<section class="games reveal" id="games">

    <div class="container">

        <!-- Title -->
        <div class="games-title">
            <h2>Game</h2>
            <p>
                Game-game favorit yang sering dimainkan di XPLAY Games
            </p>
        </div>

        <!-- Games Grid -->
        <div class="games-grid">

            <!-- Game Card -->
            <div class="game-card">
                <img src="{{ asset('images/beranda/pes.jpg') }}" alt="eFootball PES">
                <div class="game-overlay"></div>
                <h3>eFootball PES</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/naruto.jpg') }}" alt="Naruto">
                <div class="game-overlay"></div>
                <h3>Naruto</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/tekken.jpg') }}" alt="Tekken">
                <div class="game-overlay"></div>
                <h3>Tekken</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/nba.jpg') }}" alt="NBA">
                <div class="game-overlay"></div>
                <h3>NBA</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/fifa.jpg') }}" alt="FIFA">
                <div class="game-overlay"></div>
                <h3>FIFA</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/f1.jpg') }}" alt="F1">
                <div class="game-overlay"></div>
                <h3>F1</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/ittakestwo.jpg') }}" alt="It Takes Two">
                <div class="game-overlay"></div>
                <h3>It Takes Two</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/mortalkombat.jpg') }}" alt="Mortal Kombat">
                <div class="game-overlay"></div>
                <h3>Mortal Kombat</h3>
            </div>

            <div class="game-card">
                <img src="{{ asset('images/beranda/motogp.jpg') }}" alt="MotoGP">
                <div class="game-overlay"></div>
                <h3>MotoGP</h3>
            </div>

        </div>

    </div>
</section>

<!-- Dokumentasi Section -->
<section class="documentation reveal" id="dokumentasi">

    <div class="container">

        <!-- Title -->
        <div class="documentation-title">
            <h2>Dokumentasi</h2>
            <p>
                Momen seru dan suasana di XPLAY Games
            </p>
        </div>

        <!-- Carousel -->
        <div class="documentation-carousel">

            <!-- Slides -->
            <div class="documentation-slide active">
                <img src="{{ asset('images/beranda/doc1.jpg') }}" alt="Dokumentasi 1">
            </div>

            <div class="documentation-slide">
                <img src="{{ asset('images/beranda/doc2.jpg') }}" alt="Dokumentasi 2">
            </div>

            <div class="documentation-slide">
                <img src="{{ asset('images/beranda/doc3.jpg') }}" alt="Dokumentasi 3">
            </div>

            <div class="documentation-slide">
                <img src="{{ asset('images/beranda/doc4.jpg') }}" alt="Dokumentasi 4">
            </div>

            <!-- Dots -->
            <div class="documentation-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>

        </div>

    </div>
</section>

<!-- Ulasan Section -->
<section class="review reveal" id="ulasan">

    <div class="container">

        <!-- Title -->
        <div class="review-title">
            <h2>Ulasan</h2>
            <p>
                Pendapat dan pengalaman nyata dari pelanggan XPLAY Games
            </p>
        </div>

        <div class="review-filter">

            <form method="GET" action="{{ route('home') }}#ulasan">
                <select name="filter" onchange="this.form.submit()">

                    <option value="" {{ !request('filter') ? 'selected' : '' }}>
                        Semua Ulasan
                    </option>

                    <option value="rating-5" {{ request('filter') == 'rating-5' ? 'selected' : '' }}>
                        ⭐⭐⭐⭐⭐
                    </option>

                    <option value="rating-4" {{ request('filter') == 'rating-4' ? 'selected' : '' }}>
                        ⭐⭐⭐⭐
                    </option>

                    <option value="rating-3" {{ request('filter') == 'rating-3' ? 'selected' : '' }}>
                        ⭐⭐⭐
                    </option>

                    <option value="rating-2" {{ request('filter') == 'rating-2' ? 'selected' : '' }}>
                        ⭐⭐
                    </option>

                    <option value="rating-1" {{ request('filter') == 'rating-1' ? 'selected' : '' }}>
                        ⭐
                    </option>

                </select>
            </form>

            @auth
            @if(request('filter') === 'my-review')
            <a href="{{ route('home') }}#ulasan" class="btn-my-review">
                Kembali ke Semua Ulasan
            </a>
            @else
            <a href="{{ route('home', ['filter' => 'my-review']) }}#ulasan" class="btn-my-review">
                Ulasan Saya
            </a>
            @endif
            @endauth

        </div>

        <!-- Review Wrapper -->
        <div class="review-wrapper">

            <!-- LEFT : Ulasan Customer -->
            <div class="review-list">

                @if(request('filter') === 'my-review')

                <!-- Mode Ulasan Saya -->
                <div class="my-review-list">

                    @forelse($ulasan as $ulasans)

                    <div class="review-card my-review-card">

                        <form action="{{ route('ulasan.destroy', $ulasans->id) }}" method="POST"
                            class="delete-review-form">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-delete-review">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </form>

                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++) {{ $i <= $ulasans->rating ? '⭐' : '☆' }} @endfor </div> 
                            <p class="review-message">
                                {{ $ulasans->pesan }}
                            </p>

                            <h4>{{ $ulasans->user->name }}</h4>
                        </div>

                        @empty

                        <div class="review-card">
                            <p class="review-message">
                                Anda belum pernah menulis ulasan.
                            </p>
                        </div>

                        @endforelse

                    </div>

                    @else

                    {{-- SWIPER LAMA DITARUH DI SINI --}}
                    <div class="swiper reviewSwiper">
                        <div class="swiper-wrapper">

                            @forelse($ulasan as $ulasans)

                            <div class="swiper-slide">
                                <div class="review-card">

                                    <div class="review-stars">
                                        @for($i = 1; $i <= 5; $i++) {{ $i <= $ulasans->rating ? '⭐' : '☆' }} @endfor
                                            </div> <p class="review-message">
                                            {{ $ulasans->pesan }}
                                            </p>

                                            <h4>{{ $ulasans->user->name }}</h4>

                                    </div>
                                </div>

                                @empty

                                <div class="swiper-slide">
                                    <div class="review-card">
                                        <p class="review-message">
                                            Belum ada ulasan. Jadilah yang pertama!
                                        </p>
                                    </div>
                                </div>

                            @endforelse
                        </div>
                    </div>

                @endif
            </div>

            <!-- RIGHT : Form Ulasan -->
            <div class="review-form-container">
                <h3>Tulis Ulasan</h3>

                @auth
                <form method="POST" action="{{ route('ulasan.store') }}" id="review-form">
                    
                @csrf
                
                <!-- Nama -->
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly 
                    style="background: #f1f5f9;">
                </div>
                
                <!-- Rating -->
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" required>
                        <option value="5">⭐⭐⭐⭐⭐</option>
                        <option value="4">⭐⭐⭐⭐</option>
                        <option value="3">⭐⭐⭐</option>
                        <option value="2">⭐⭐</option>
                        <option value="1">⭐</option>
                    </select>
                    
                    @error('rating')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pesan -->
                <div class="form-group">
                    <label>Ulasan</label>
                    <textarea name="pesan" rows="5" required
                    placeholder="Tulis ulasan Anda...">{{ old('pesan') }}</textarea>
                    
                    @error('pesan')
                    <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Button -->
                <button type="submit" class="btn-primary">
                    Kirim Ulasan
                </button>
                </form>
                
                @else
                <div class="review-login-prompt">
                    <i class="fa-solid fa-lock"></i>
                    <p>Silakan masuk terlebih dahulu untuk menulis ulasan.</p>
                    <a href="{{ route('login') }}" class="btn-primary">
                        Masuk Sekarang
                    </a>
                </div>
                @endauth

            </div>

        </div>

    </div>
</section>

<!-- Kontak Section -->
<section class="contact reveal" id="kontak">

    <div class="container">

        <!-- Title -->
        <div class="contact-title">
            <h2>Kontak</h2>
        </div>

        <!-- Wrapper -->
        <div class="contact-wrapper">

            <!-- LEFT SIDE -->
            <div class="contact-info">

                <!-- Alamat -->
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div class="contact-text">
                        <h4>Alamat</h4>
                        <p>
                            Ruko Batam Central Park, Blok B No.8E, Tj. Uma,
                            Kec. Lubuk Baja, Kota Batam, Kepulauan Riau 29445
                        </p>
                    </div>
                </div>

                <!-- Jam Operasional -->
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>

                    <div class="contact-text">
                        <h4>Jam Operasional</h4>
                        <p>
                            10.00 - 03.00 WIB<br>
                            (Setiap Hari)
                        </p>
                    </div>
                </div>

                <!-- Telepon -->
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div class="contact-text">
                        <h4>Telepon</h4>
                        <p>
                            0851-9434-5274
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <div class="contact-text">
                        <h4>Email</h4>
                        <p>
                            xplaygames@gmail.com
                        </p>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="social-media">
                    <h3>Media Sosial</h3>

                    <div class="social-icons">

                        <a href="https://www.facebook.com/xplaygamesbatam" target="_blank" class="facebook">
                            <i class="fa-brands fa-facebook"></i>
                        </a>

                        <a href="https://www.instagram.com/xplaygamesbatam?igsh=MW1pZzVldjF4cW12dA==" target="_blank"
                            class="instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>

                        <a href="https://t.me/XPlaygamesBatam" target="_blank" class="telegram">
                            <i class="fa-brands fa-telegram"></i>
                        </a>

                        <a href="https://www.tiktok.com/@xplaygamesbatam?_r=1&_t=ZS-96ExikHEFQe" target="_blank"
                            class="tiktok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>

                        <a href="https://wa.me/6285194345274" target="_blank" class="whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>

                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="contact-map">
                
                <h3>Lokasi XPLAY Games</h3>

                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.023979534554!2d104.0030448!3d1.1433303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d989de24c4b731%3A0x19e389ffaf434a0b!2sXPlay%20Games!5e0!3m2!1sid!2sid!4v1778343825508!5m2!1sid!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>

        </div>

    </div>
</section>

@push('scripts')
<script>
    // Swiper Carousel Ulasan
    const reviewSwiperElement = document.querySelector('.reviewSwiper');

    if (reviewSwiperElement) {
        new Swiper('.reviewSwiper', {
            direction: 'vertical',
            slidesPerView: 3,
            spaceBetween: 24,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            loop: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                769: {
                    slidesPerView: 3,
                }
            }
        });
    }

    // Notifikasi berhasil masuk
    document.addEventListener('DOMContentLoaded', function () {
        const alertBottomLeft = document.querySelector('.alert-bottom-left');
        if (alertBottomLeft) {
            // Mulai dari luar layar
            alertBottomLeft.style.transition = 'left 0.5s ease, opacity 0.5s ease';

            // Animasi masuk
            setTimeout(() => {
                alertBottomLeft.style.left = '30px';
                alertBottomLeft.style.opacity = '1';
            }, 0);

            // Animasi keluar
            setTimeout(() => {
                alertBottomLeft.style.left = '-300px';
                alertBottomLeft.style.opacity = '0';
                setTimeout(() => alertBottomLeft.remove(), 500);
            }, 4000);
        }
    });

    // Loading saat submit form ulasan
    @auth
    const reviewForm = document.getElementById('review-form');

    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {

            if (!reviewForm.checkValidity()) {
                return;
            }

            Swal.fire({
                title: 'Mengirim ulasan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    }
    @endauth
</script>

<script>
    document.querySelectorAll('.delete-review-form').forEach(form => {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            Swal.fire({
                title: 'Hapus Ulasan?',
                text: 'Ulasan yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

    });
</script>
@endpush

@endsection