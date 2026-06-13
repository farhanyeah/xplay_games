    @extends('layouts.main')

    @section('title', 'Sewa PlayStation | XPLAY Games')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/sewa.css') }}">
    @endpush

    @section('content')

    <!-- Hero Section -->
    <section class="sewa-hero">
        <div class="container sewa-hero-wrapper">
            <div class="sewa-hero-content reveal">
                <p class="sewa-subtitle">
                    LAYANAN RENTAL XPLAY
                </p>
                <h1>
                    Sewa PlayStation Lebih Mudah dan Praktis
                </h1>
                <p class="sewa-description">
                    Cek ketersediaan unit PlayStation secara real-time,
                    lakukan sewa online, dan nikmati pengalaman gaming terbaik
                    bersama XPLAY Games
                </p>
            </div>
        </div>
    </section>

    <!-- Tabel Unit PS yang Tersedia -->
    <section class="unit-section" id="unit-table">
        <div class="container">

            <div class="section-heading reveal">
                <h2>Daftar Unit PlayStation</h2>
                <p>
                    Informasi unit PlayStation dan status ketersediaan saat ini
                </p>
            </div>

            <div class="table-container reveal">
                <table class="xplay-table">
                    <thead>
                        <tr>
                            <th>Kode Unit</th>
                            <th>Tipe</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($units as $unit)
                        <tr>
                            <td>{{ $unit->kode_unit }}</td>
                            <td>{{ $unit->jenisUnit->tipe }}</td>
                            <td>
                                @if($unit->status == 'available')
                                <span class="badge available">
                                    Tersedia
                                </span>
                                @else
                                <span class="badge unavailable">
                                    Tidak Tersedia
                                </span>
                                @endif
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="3" class="text-center">
                                Tidak ada unit tersedia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </section>

    <!-- Riwayat Sewa buat Publik -->
    @guest
    <section class="guest-section">
        <div class="container">

            <div class="section-heading reveal">
                <h2>Riwayat Sewa</h2>
                <p>
                    Riwayat sewa PlayStation di XPLAY Games
                </p>
            </div>

            <div class="table-container reveal">
                <table class="xplay-table">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayatPublik->where('status_sewa', 'completed') as $riwayat)
                        <tr>
                            <td>{{ $riwayat->unitSewa->jenisUnit->tipe }}</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_mulai)->format('d M Y, H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('d M Y, H:i') }}</td>
                            <td>
                                @switch($riwayat->status_sewa)
                                @case('pending')
                                <span class="badge pending">Diproses</span>
                                @break
                                @case('disewa')
                                <span class="badge rented">Disewa</span>
                                @break
                                @case('extended')
                                <span class="badge extended">Diperpanjang</span>
                                @break
                                @case('completed')
                                <span class="badge completed">Selesai</span>
                                @break
                                @case('cancelled')
                                <span class="badge cancelled">Dibatalkan</span>
                                @break
                                @endswitch
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada riwayat sewa
                            </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination Publilk -->
            @if($totalHalamanPublik > 1)
            <div class="custom-pagination">
                <button type="button" class="page-btn" onclick="ubahHalamanPublik({{ $halamanPublik - 1 }})"
                    {{ $halamanPublik == 1 ? 'disabled' : '' }}>
                    &laquo; Prev
                </button>

                @for($i = 1; $i <= $totalHalamanPublik; $i++) @if($i==1 || $i==$totalHalamanPublik || ($i>=
                    $halamanPublik - 2 && $i <= $halamanPublik + 2)) <button type="button"
                        class="page-btn {{ $i == $halamanPublik ? 'active' : '' }}"
                        onclick="ubahHalamanPublik({{ $i }})">
                        {{ $i }}
                        </button>
                        @elseif($i == $halamanPublik - 3 || $i == $halamanPublik + 3)
                        <span class="page-ellipsis">...</span>
                        @endif
                        @endfor

                        <button type="button" class="page-btn" onclick="ubahHalamanPublik({{ $halamanPublik + 1 }})"
                            {{ $halamanPublik == $totalHalamanPublik ? 'disabled' : '' }}>
                            Next &raquo;
                        </button>
            </div>

            <div style="text-align:center;margin-top:10px;font-size:14px;color:#64748b;">
                Menampilkan {{ $riwayatPublik->count() }} dari {{ $totalPublik }} data
            </div>
            @endif

            <div class="guest-login-box reveal">
                <h3>
                    Masuk untuk menyewa unit PlayStation
                </h3>
                <a href="{{ route('login') }}" class="guest-login-button">
                    Masuk Sekarang
                </a>
            </div>

        </div>
    </section>
    @endguest

    <!-- Customer Section -->
    @auth
    @if(in_array(auth()->user()->role, ['customer', 'staf', 'owner']))

    <!-- Form Sewa -->
    <section class="rental-form-section">

        <div class="container">

            <div class="section-heading reveal">
                <h2>Form Sewa PlayStation</h2>
                <p>
                    Lengkapi data berikut untuk melakukan sewa unit PlayStation
                </p>
            </div>

            <div class="rental-form-card reveal">
                <form method="POST" action="{{ route('sewa.store') }}" id="rentalForm" novalidate>
                    @csrf

                    <div class="form-grid">

                        <!-- Nama Penyewa -->
                        <div class="form-group">
                            <label>Nama <span class="required">*</span></label>
                            @if(auth()->user()->role === 'customer')
                            <input type="text" name="nama" value="{{ Auth::user()->name }}" readonly
                                style="background: rgba(255,255,255,0.04);">
                            @else
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                placeholder="Masukkan nama penyewa" required>
                            @endif
                            @error('nama')
                            <span class="error-text"> {{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No HP -->
                        <div class="form-group">
                            <label>No HP <span class="required">*</span></label>
                            <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                                placeholder="Contoh: 081234567890" required>
                            <span class="error-message" data-input="no_hp"></span>
                            @error('no_hp')
                            <span class="error-text"> {{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div class="form-group">
                            <label>Unit PlayStation <span class="required">*</span></label>
                            <select name="unit_id" id="unitSelect" required>
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                @if($unit->status == 'available')
                                <option value="{{ $unit->id }}" data-kode="{{ $unit->kode_unit }}"
                                    data-jenis="{{ $unit->jenis_unit_id }}"
                                    data-jaminan="{{ $unit->jenisUnit->harga_jaminan }}">
                                    {{ $unit->kode_unit }} - {{ $unit->jenisUnit->tipe }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            <span class="error-message" data-input="unit_id"></span>
                            @error('unit_id')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Paket Durasi -->
                        <div class="form-group">
                            <label>Paket Durasi <span class="required">*</span></label>
                            <select name="paket_id" id="paketSelect" required>
                                <option value="">Pilih Paket</option>
                                @foreach($paketHarga as $paket)
                                <option value="{{ $paket->id }}" data-jenis="{{ $paket->jenis_unit_id }}"
                                    data-harga="{{ $paket->harga }}" data-hari="{{ $paket->durasi_hari }}">
                                    {{ $paket->durasi_hari }} Hari - Rp {{ number_format($paket->harga, 0, ',', '.') }}
                                </option>
                                @endforeach
                                <option value="lainnya">
                                    Lainnya (Atur Jumlah Hari)
                                </option>
                            </select>
                            <span class="error-message" data-input="paket_id"></span>
                            @error('paket_id')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Durasi Custom (muncul jika pilih Lainnya) -->
                        <div class="form-group" id="durasiCustomGroup" style="display: none;">
                            <label>Jumlah Hari <span class="required">*</span></label>
                            <input type="number" name="durasi_custom" id="durasiCustom" min="1"
                                placeholder="Masukkan jumlah hari">
                            <small class="from-hint">
                                Harga dihitung dari harga 1 hari × jumlah hari
                            </small>
                            <span class="error-message" data-input="durasi_custom"></span>
                            @error('durasi_custom')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="form-group">
                            <label>Tanggal & Waktu Mulai <span class="required">*</span></label>
                            <input type="datetime-local" name="tanggal_mulai" id="startDate" required>
                            <span class="error-message" data-input="tanggal_mulai"></span>
                            @error('tanggal_mulai')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="form-group">
                            <label>Tanggal & Waktu Selesai <span class="required">*</span></label>
                            <input type="datetime-local" name="tanggal_selesai" id="endDate" readonly required>
                            <small class="from-hint">
                                Akan dihitung otomatis berdasarkan durasi
                            </small>
                            <span class="error-message" data-input="tanggal_selesai"></span>
                            @error('tanggal_selesai')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pembayaran -->
                        @if(auth()->user()->role === 'customer')
                        <div class="form-group">
                            <label>Pembayaran <span class="required">*</span></label>
                            <select name="pembayaran" id="paymentSelect" required>
                                <option value="">Pilih Pembayaran</option>
                                <option value="midtrans">Midtrans</option>
                                <option value="cash">Tunai</option>
                            </select>

                            <span class="error-message" data-input="pembayaran"></span>
                            @error('pembayaran')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        @else
                        <input type="hidden" name="pembayaran" id="paymentSelect" value="cash">
                        @endif
                        

                        <!-- Jenis Jaminan -->
                        <div class="form-group">
                            <label>Jenis Jaminan <span class="required">*</span></label>
                            <select name="guarantee_type" id="guaranteeSelect" required>
                                <option value="">Pilih Jaminan</option>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="KTM">KTM</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <span class="error-message" data-input="guarantee_type"></span>
                            @error('guarantee_type')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Jaminan Lainnya -->
                        <div class="form-group" id="guaranteeOtherGroup" style="display: none;">
                            <label>Keterangan Jaminan <span class="required">*</span></label>
                            <input type="text" name="guarantee_other" id="guaranteeOther"
                                placeholder="Masukkan jenis jaminan">
                            <span class="error-message" data-input="guarantee_other"></span>
                            @error('guarantee_other')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group full-width">
                            <label>Alamat Rumah <span class="required">*</span></label>
                            <textarea name="alamat" rows="6" placeholder="Masukkan alamat lengkap"
                                required>{{ old('alamat') }}</textarea>
                            <span class="error-message" data-input="alamat"></span>
                            @error('alamat')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Harga Jaminan -->
                        <div class="form-group">
                            <label>Harga Jaminan</label>
                            <div class="harga-display" id="jaminanDisplay">
                                Rp ...
                            </div>
                            <input type="hidden" name="harga_jaminan" id="hargaJaminan">
                            <small class="from-hint">
                                Jaminan kembali saat unit PS dikembalikan
                            </small>
                        </div>

                        <!-- Harga Sewa -->
                        <div class="form-group">
                            <label>Harga Sewa</label>
                            <div class="harga-display" id="sewaDisplay">
                                Rp ...
                            </div>
                            <input type="hidden" name="harga_sewa" id="hargaSewa">
                        </div>

                        <!-- Total Harga -->
                        <div class="form-group harga-right">
                            <label>Total Harga</label>
                            <div class="harga-display" id="hargaDisplay">
                                Rp ...
                            </div>
                            <input type="hidden" name="total_harga" id="totalHarga">
                            <small class="from-hint">Harga Sewa + Harga Jaminan</small>
                        </div>
                    </div>

                    <button type="button" class="submit-button" id="openConfirm">
                        Ajukan Sewa
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Sewa -->
    <div class="confirm-modal" id="confirmModal">

        <div class="confirm-modal-content">

            <h2>Konfirmasi Sewa</h2>
            <p>
                Pastikan data sewa sudah benar sebelum melanjutkan
            </p>

            <div class="confirm-summary">
                <div class="summary-item">
                    <span>Unit</span>
                    <strong id="confirmUnit">-</strong>
                </div>

                <div class="summary-item">
                    <span>Pembayaran</span>
                    <strong id="confirmPayment">-</strong>
                </div>

                <div class="summary-item">
                    <span>Durasi</span>
                    <strong id="confirmDurasi">-</strong>
                </div>

                <div class="summary-item">
                    <span>Tanggal Mulai</span>
                    <strong id="confirmStart">-</strong>
                </div>

                <div class="summary-item">
                    <span>Tanggal Selesai</span>
                    <strong id="confirmEnd">-</strong>
                </div>

                <div class="summary-item">
                    <span>Harga Sewa</span>
                    <strong id="confirmHargaSewa">-</strong>
                </div>

                <div class="summary-item">
                    <span>Harga Jaminan</span>
                    <strong id="confirmHargaJaminan">-</strong>
                </div>

                <div class="summary-item">
                    <span>Total Harga</span>
                    <strong id="confirmHarga">-</strong>
                </div>
            </div>

            <div class="confirm-actions">
                <button type="button" class="cancel-button" id="cancelModal">
                    Batal
                </button>

                <button type="button" class="confirm-button" id="confirmSubmit">
                    Konfirmasi Sewa
                </button>
            </div>

        </div>
    </div>

    <!-- Data Customer yang Sewa Unit PS -->
    <section class="customer-history-section" id="customer-history">
        <div class="container">

            <div class="section-heading reveal">
                @if(auth()->check() && auth()->user()->role !== 'customer')
                <h2>Data Sewa Pelanggan</h2>
                <p>
                    Riwayat sewa yang Anda buat untuk pelanggan
                </p>
                @else
                <h2>Data Sewa Saya</h2>
                <p>
                    Riwayat sewa PlayStation milik Anda
                </p>
                @endif
            </div>

            <div class="table-container reveal">
                <table class="xplay-table">

                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Unit</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Status</th>
                            @if(auth()->user()->role === 'customer')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($sewaSaya as $sewa)
                        @php
                        $durasiAwal = $sewa->paket_id
                        ? $sewa->paket->durasi_hari . ' Hari'
                        : $sewa->durasi_custom . ' Hari (Custom)';

                        $totalTambah = $sewa->tambahDurasi->where('payment_status', 'paid')->sum('tambah_hari');

                        $durasiText = $totalTambah > 0
                        ? $durasiAwal . ' + ' . $totalTambah . ' Hari'
                        : $durasiAwal;

                        $harga1Hari = $sewa->unitSewa->jenisUnit->paketHarga
                        ->where('durasi_hari', 1)->first()->harga ?? 0;
                        $hargaTotal = $sewa->harga_sewa + ($totalTambah * $harga1Hari);

                        $tambahDurasiUnpaid = in_array($sewa->status_sewa, ['disewa', 'extended'])
                        ? $sewa->tambahDurasi->where('payment_status', 'unpaid')->first()
                        : null;
                        @endphp

                        <tr>
                            <td>
                                <a href="#" class="transaction-link" data-id="{{ $sewa->id }}"
                                    data-code="{{ $sewa->transaction_code }}"
                                    data-unit="{{ $sewa->unitSewa->kode_unit }} - {{ $sewa->unitSewa->jenisUnit->tipe }}"
                                    data-durasi="{{ $durasiText }}" data-nama="{{ $sewa->nama }}"
                                    data-no-hp="{{ $sewa->no_hp }}" data-alamat="{{ $sewa->alamat }}"
                                    data-jaminan-type="{{ $sewa->guarantee_type }}"
                                    data-jaminan-other="{{ $sewa->guarantee_other ?? '-' }}"
                                    data-mulai="{{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->format('d M Y, H:i') }}"
                                    data-selesai="{{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->format('d M Y, H:i') }}"
                                    data-harga-sewa="Rp {{ number_format($hargaTotal, 0, ',', '.') }}"
                                    data-harga-jaminan="Rp {{ number_format($sewa->harga_jaminan, 0, ',', '.') }}"
                                    data-total="Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}"
                                    data-pembayaran="{{ ucfirst($sewa->pembayaran) }}"
                                    data-payment-status="{{ $sewa->payment_status }}"
                                    data-status="{{ $sewa->status_sewa }}">
                                    {{ $sewa->transaction_code }}
                                </a>
                            </td>

                            <td>{{ $sewa->unitSewa->kode_unit }} - {{ $sewa->unitSewa->jenisUnit->tipe }}</td>
                            <td>{{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->format('d M Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->format('d M Y H:i') }}</td>
                            <td>
                                @switch($sewa->status_sewa)
                                @case('pending')
                                <span class="badge pending">Diproses</span>
                                @break
                                @case('disewa')
                                <span class="badge rented">Disewa</span>
                                @break
                                @case('extended')
                                <span class="badge extended">Diperpanjang</span>
                                @break
                                @case('completed')
                                <span class="badge completed">Selesai</span>
                                @break
                                @case('cancelled')
                                <span class="badge cancelled">Dibatalkan</span>
                                @break
                                @endswitch
                            </td>

                            @if(auth()->user()->role === 'customer')
                            <td>
                                @if($tambahDurasiUnpaid)
                                <button type="button" class="btn-icon btn-bayar-tambah-durasi"
                                title="Bayar Tambah Durasi"
                                    data-tambah-durasi-id="{{ $tambahDurasiUnpaid->id }}"
                                    data-snap-token="{{ $tambahDurasiUnpaid->midtrans_token }}">
                                    <i class="fas fa-credit-card"></i>
                                </button>

                                @elseif(in_array($sewa->status_sewa, ['disewa', 'extended']))
                                <button type="button" class="btn-icon btn-tambah-durasi-customer"
                                title="Tambah Durasi Sewa"
                                    data-sewa-id="{{ $sewa->id }}" data-kode="{{ $sewa->transaction_code }}"
                                    data-jenis-unit-id="{{ $sewa->unitSewa->jenis_unit_id }}">
                                    <i class="fas fa-plus"></i>
                                </button>

                                @elseif($sewa->status_sewa === 'pending' && $sewa->pembayaran === 'midtrans'
                                && $sewa->payment_status === 'unpaid')
                                <button type="button" class="btn-icon btn-bayar-sekarang"
                                title="Bayar Sekarang"
                                    data-sewa-id="{{ $sewa->id }}"
                                    data-snap-token="{{ $sewa->midtrans_token }}">
                                    <i class="fas fa-credit-card"></i>
                                </button>

                                @else
                                <span style="color: #94a3b8; font-size: 12px;">—</span>
                                @endif
                            </td>
                            @endif
                        </tr>

                        @empty

                        <tr>
                            <td colspan="{{ auth()->user()->role === 'customer' ? 6 : 5 }}" class="text-center">
                                Belum ada data sewa
                            </td>
                        </tr>

                        @endforelse
                    </tbody>

                </table>

            </div>

             <!-- Custom Pagination -->
        @if($totalHalaman > 1)
        <div class="custom-pagination">
            <!-- Prev Button -->
            <button type="button" class="page-btn" 
                onclick="ubahHalaman({{ $halaman - 1 }})" 
                {{ $halaman == 1 ? 'disabled' : '' }}>
                &laquo; Prev
            </button>
            
            <!-- Page Numbers -->
            @for($i = 1; $i <= $totalHalaman; $i++)
                @if($i == 1 || $i == $totalHalaman || ($i >= $halaman - 2 && $i <= $halaman + 2))
                    <button type="button" class="page-btn {{ $i == $halaman ? 'active' : '' }}" 
                        onclick="ubahHalaman({{ $i }})">
                        {{ $i }}
                    </button>
                @elseif($i == $halaman - 3 || $i == $halaman + 3)
                    <span class="page-ellipsis">...</span>
                @endif
            @endfor

            <!-- Next Button -->
            <button type="button" class="page-btn" 
                onclick="ubahHalaman({{ $halaman + 1 }})" 
                {{ $halaman == $totalHalaman ? 'disabled' : '' }}>
                Next &raquo;
            </button>
        </div>
        
        <!-- Info Text -->
        <div style="text-align:center;margin-top:10px;font-size:14px;color:#64748b;">
            Menampilkan {{ $sewaSaya->count() }} dari {{ $totalSewa }} data
        </div>
        @endif


        </div>
    </section>
    @endif
    @endauth

    <!-- Modal Tambah Durasi -->
    @if(auth()->check() && auth()->user()->role === 'customer')
    <div class="confirm-modal" id="tambahDurasiModal">

        <div class="confirm-modal-content">
            <h2>Tambah Durasi Sewa</h2>
            <p>Tambah durasi sewa PlayStation Anda</p>

            <div class="confirm-summary">
                <div class="summary-item">
                    <span>Kode Transaksi</span>
                    <strong id="tdKode">-</strong>
                </div>
                <div class="summary-item">
                    <span>Jumlah Hari Tambahan</span>
                    <strong id="tdHariPreview">-</strong>
                </div>
                <div class="summary-item">
                    <span>Biaya Tambahan</span>
                    <strong id="tdHargaPreview">Rp ...</strong>
                </div>
            </div>

            <div class="form-groups">
                <label>Jumlah Hari Tambahan</label>
                <input type="number" id="tdHariInput" min="1" placeholder="Masukkan jumlah hari"
                    class="tambah-durasi-input">
            </div>

            <div class="confirm-actions">
                <button type="button" class="cancel-button" id="closeTambahDurasiModal">Batal</button>
                <button type="button" class="confirm-button" id="btnProsesTambahDurasi">
                    Bayar via Midtrans
                </button>
            </div>

        </div>
    </div>
    @endif

    <!-- Modal Detail Transaksi -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content">

            <button class="modal-close" id="closeDetailModal">&times;</button>

            <h2 class="modal-title">
                Detail Sewa
            </h2>
            <p class="modal-subtitle">
                Informasi lengkap transaksi sewa Anda
            </p>

            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Kode Transaksi</span>
                    <strong class="detail-value" id="detailCode">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Unit</span>
                    <strong class="detail-value" id="detailUnit">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nama Penyewa</span>
                    <strong class="detail-value" id="detailNama">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">No HP</span>
                    <strong class="detail-value" id="detailHp">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pembayaran</span>
                    <strong class="detail-value" id="detailPembayaran">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Durasi</span>
                    <strong class="detail-value" id="detailDurasi">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Mulai</span>
                    <strong class="detail-value" id="detailMulai">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Selesai</span>
                    <strong class="detail-value" id="detailSelesai">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jenis Jaminan</span>
                    <strong class="detail-value" id="detailJaminanType">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Keterangan Jaminan</span>
                    <strong class="detail-value" id="detailJaminanOther">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Harga Sewa</span>
                    <strong class="detail-value" id="detailHargaSewa">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Harga Jaminan</span>
                    <strong class="detail-value" id="detailHargaJaminan">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Harga</span>
                    <strong class="detail-value" id="detailTotal">-</strong>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status Pembayaran</span>
                    <strong class="detail-value" id="detailPaymentStatus">-</strong>
                </div>
                <div class="detail-item full-width">
                    <span class="detail-label">Alamat</span>
                    <strong class="detail-value" id="detailAlamat">-</strong>
                </div>
                <div class="detail-item full-width">
                    <span class="detail-label">Status Sewa</span>
                    <strong class="detail-value" id="detailStatus">-</strong>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" id="closeDetailModalBtn">Tutup</button>
            </div>

        </div>
    </div>


    @php
    $harga1HariMap = [];
    foreach($paketHarga->groupBy('jenis_unit_id') as $jenisId => $pakets) {
    $paket1Hari = $pakets->firstWhere('durasi_hari', 1);
    $harga1HariMap[$jenisId] = $paket1Hari ? $paket1Hari->harga : 0;
    }
    @endphp

    @push('scripts')
    <script>
    window.harga1HariMap = @json($harga1HariMap);
    window.sewaStoreUrl = "{{ route('sewa.store') }}";
    </script>

    <script>
    function ubahHalaman(halaman) {
        if(halaman < 1) return;
        const url = new URL(window.location.href);
        url.searchParams.set('halaman', halaman);
        window.location.href = url.toString();
    }

    function ubahHalamanPublik(halaman) {
        if(halaman < 1) return;
        const url = new URL(window.location.href);
        url.searchParams.set('halaman_publik', halaman);
        window.location.href = url.toString();
    }
    </script>


    @endpush

    @endsection