@extends('layouts.main')

@section('title', 'Informasi | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/info.css') }}">
@endpush

@section('content')

<!-- Info Hero -->
<section class="info-hero">

    <div class="container">

        <div class="info-hero-content reveal">
            <div class="section-label center-label">
                <p>INFORMASI</p>
                <span></span>
            </div>

            <h1>Informasi XPLAY</h1>
            <p>
                Temukan semua yang kamu butuhkan — mulai dari cara booking
                online, sewa PlayStation, hingga aturan selama berada
                di XPLAY Games
            </p>

        </div>
    </div>
</section>

<!-- Info Content -->
<section class="info-section">

    <div class="container">

        <div class="info-wrapper">

            <!-- Cara Booking Online -->
            <div class="info-card reveal">
                <div class="info-icon">
                    <i data-lucide="monitor"></i>
                </div>

                <h2>Cara Booking Online</h2>
                <hr>

                <div class="info-steps">

                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-text">
                            <h3>Cek Ketersediaan</h3>
                            <p>
                                Cek jadwal dan ruangan yang tersedia 
                                langsung melalui website
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-text">
                            <h3>Konfirmasi Jadwal</h3>
                            <p>
                                Hubungi admin untuk konfirmasi jadwal dan
                                ruangan yang dipilih
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-text">
                            <h3>Isi Form Booking</h3>
                            <p>
                                Lengkapi data booking sesuai
                                form yang tersedia
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-text">
                            <h3>Pembayaran</h3>
                            <p>
                                Lakukan pembayaran sesuai nominal yang
                                tertera
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">5</div>
                        <div class="step-text">
                            <h3>Selesai</h3>
                            <p>
                                Booking berhasil! Tinggal
                                datang sesuai waktu yang telah
                                dipilih
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Cara Sewa PlayStation -->
            <div class="info-card reveal">
                <div class="info-icon">
                    <i data-lucide="gamepad-2"></i>
                </div>

                <h2>Cara Sewa Unit PlayStation</h2>
                <hr>

                <div class="info-steps">

                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-text">
                            <h3>Cek Ketersediaan</h3>
                            <p>
                                Lihat unit PlayStation yang tersedia langsung
                                melalui website
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-text">
                            <h3>Konfirmasi Unit</h3>
                            <p>
                                Hubungi admin untuk konfirmasi unit yang
                                ingin disewa
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-text">
                            <h3>Datang ke Lokasi</h3>
                            <p>
                                Kunjungi lokasi untuk memeriksa kondisi unit  
                                secara langsung
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-text">
                            <h3>Isi Form Sewa</h3>
                            <p>
                                Lengkapi data sewa sesuai
                                form yang tersedia
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">5</div>
                        <div class="step-text">
                            <h3>Pembayaran</h3>
                            <p>
                                Lakukan pembayaran sesuai nominal 
                                yang tertera
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">6</div>
                        <div class="step-text">
                            <h3>Jaminan</h3>
                            <p>
                                Memberikan uang jaminan sesuai tipe PlayStation yang disewa 
                                beserta identitas diri (KTP, SIM, atau yang lainnya)
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">7</div>
                        <div class="step-text">
                            <h3>Selesai</h3>
                            <p>
                                Unit PlayStation siap dibawa! Kembalikan sesuai waktu
                                yang tertera di form
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Aturan di XPLAY Games -->
            <div class="info-card reveal">
                <div class="info-icon">
                    <i data-lucide="shield-alert"></i>
                </div>
                
                <h2>Aturan XPLAY Games</h2>
                <hr>

                <div class="rules-list">

                    <div class="rule-item">
                        <div class="rule-icon">
                            <i data-lucide="cigarette-off"></i>
                        </div>
                        <div class="rule-content">
                            <h3>Dilarang merokok di area lantai 2</h3>
                        </div>
                    </div>

                    <div class="rule-item">
                        <div class="rule-icon">
                            <i data-lucide="cup-soda"></i>
                        </div>
                        <div class="rule-content">
                            <h3>Dilarang membawa minuman dari luar</h3>
                        </div>
                    </div>

                    <div class="rule-item">
                        <div class="rule-icon">
                            <i data-lucide="volume-2"></i>
                        </div>
                        <div class="rule-content">
                            <h3>Dilarang membuat keributan</h3>
                        </div>
                    </div>

                    <div class="rule-item">
                        <div class="rule-icon">
                            <i data-lucide="triangle-alert"></i>
                        </div>
                        <div class="rule-content">
                            <h3>Dilarang merusak fasilitas</h3>
                        </div>
                    </div>

                    <div class="rule-item">
                        <div class="rule-icon">
                            <i data-lucide="footprints"></i>
                        </div>
                        <div class="rule-content">
                            <h3>Letakkan sandal dan sepatu di rak yang tersedia</h3>
                        </div>
                    </div>

                    <div class="rule-item">
                        <div class="rule-icon">
                            <i data-lucide="trash"></i>
                        </div>
                        <div class="rule-content">
                            <h3>Buang sampah pada tempatnya</h3>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
    </div>
</section>

@endsection