@extends('layouts.operational')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/operational/ops.css') }}">
<style>
    /* Override style untuk laporan */
    .laporan-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 24px;
        margin-bottom: 20px;
    }
    .laporan-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }
    .laporan-field {
        margin-bottom: 18px;
    }
    .laporan-field label {
        font-size: 12px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 6px;
        display: block;
    }
    .laporan-field .form-control {
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        border: 1px solid #e2e8f0;
    }
    .laporan-field .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .laporan-field .form-control[readonly] {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
        border-color: #c7d2fe;
        font-weight: 700;
        color: #1e293b;
    }
    .laporan-pendapatan-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .laporan-pendapatan-box .label {
        font-size: 12px;
        font-weight: 600;
        opacity: 0.95;
        letter-spacing: 1px;
    }
    .laporan-pendapatan-box .value {
        font-size: 32px;
        font-weight: 800;
    }
    .pengeluaran-box {
        background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
        border: 1px solid #fecaca;
        padding: 18px;
        border-radius: 12px;
    }
    .pengeluaran-box .laporan-section-title {
        color: #dc2626;
        border-color: #fecaca;
    }
    .pengeluaran-box label {
        color: #991b1b;
    }
    .btn-generate-laporan {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 14px 24px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .btn-generate-laporan:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .btn-download-pdf {
        background: #dc2626;
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
    }
    .btn-download-pdf:hover {
        background: #b91c1c;
    }
    .info-box {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 10px;
        padding: 16px;
    }
    .info-box .title {
        font-weight: 700;
        color: #166534;
        margin-bottom: 8px;
    }
    .info-box p {
        color: #15803d;
        font-size: 13px;
        margin: 0;
    }
</style>
@endpush

@section('title', 'Laporan | XPLAY Games')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ops-page-title">
        <i></i>Laporan
    </h1>
    
    <div class="d-flex" style="gap: 10px;">
        <a href="{{ route('operational.laporan.riwayat') }}" class="btn ops-btn-reset">
            <i class="fas fa-history mr-1"></i>Riwayat Laporan
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<!-- Filter Tanggal -->
<div class="card ops-card mb-4">
    <div class="card-header ops-card-header">
        <i class="fas fa-calendar mr-2 ops-header-icon"></i>
        <h6 class="m-0 font-weight-bold ops-header-title">Pilih Tanggal Laporan</h6>
    </div>
    <div class="card-body ops-card-body">
        <form method="GET" action="{{ route('operational.laporan') }}">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="ops-filter-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control ops-input" 
                        value="{{ $tanggal }}" max="{{ now()->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn ops-btn-filter">
                        <i class="fas fa-search mr-1"></i>Cek Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($laporanExists)
<!-- SUDAH ADA LAPORAN -->
<div class="laporan-card">
    <div class="text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-4" style="opacity: 0.8;"></i>
        <h4 style="color: #1e293b; margin-bottom: 8px;">Laporan Sudah Dimuat!</h4>
        <p style="color: #64748b;">Laporan untuk tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong> sudah digenerate sebelumnya.</p>
        <div class="info-box text-left d-inline-block mt-3">
            <div class="title"><i class="fas fa-info-circle mr-1"></i>Informasi</div>
            <p>Silakan pilih tanggal lain jika ingin membuat laporan baru, atau hubungi owner jika ingin regenerate.</p>
        </div>
    </div>
</div>
@else
<!-- FORM GENERATE LAPORAN -->
<form method="POST" action="{{ route('operational.laporan.generate') }}" id="laporanForm">
    @csrf
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

    <div class="row">
        <!-- KIRI: PENDAPATAN -->
        <div class="col-lg-6 mb-4">
            <div class="laporan-card">
                <div class="laporan-section-title" style="color: #667eea;">
                    <i class="fas fa-arrow-down mr-1"></i>Pendapatan (Auto Terisi)
                </div>

                <div class="laporan-field">
                    <label><i class="fas fa-gamepad mr-1"></i>Pendapatan Billing</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($pendapatanBilling, 0, ',', '.') }}" readonly>
                </div>

                <div class="laporan-field">
                    <label><i class="fas fa-handshake mr-1"></i>Pendapatan Sewa</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($pendapatanSewa, 0, ',', '.') }}" readonly>
                </div>

                <div class="laporan-field">
                    <label><i class="fas fa-calendar-check mr-1"></i>Pendapatan Booking</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($pendapatanBooking, 0, ',', '.') }}" readonly>
                </div>

                <div class="laporan-field">
                    <label><i class="fas fa-shopping-cart mr-1"></i>Pendapatan Penjualan</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($pendapatanPenjualan, 0, ',', '.') }}" readonly>
                </div>

                <div class="laporan-pendapatan-box">
                    <div class="label">TOTAL PENDAPATAN</div>
                    <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- KANAN: PENGELUARAN & KAS -->
        <div class="col-lg-6 mb-4">
            <div class="laporan-card">
                <div class="laporan-section-title" style="color: #48bb78;">
                    <i class="fas fa-wallet mr-1"></i>Kas & Pengeluaran
                </div>

                <div class="laporan-field">
                    <label><i class="fas fa-university mr-1"></i>Buka Kas (Saldo Cash Kemarin)</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($bukaKas, 0, ',', '.') }}" readonly>
                    <input type="hidden" name="buka_kas" value="{{ $bukaKas }}">
                    <small class="text-muted">Saldo cash dari hari sebelumnya</small>
                </div>

                <div class="pengeluaran-box">
                    <div class="laporan-section-title" style="margin-top: 0;">
                        <i class="fas fa-arrow-up mr-1"></i>Pengeluaran
                    </div>

                    <div class="laporan-field">
                        <label><i class="fas fa-user-clock mr-1"></i>Uang Part Time</label>
                        <input type="number" name="pengeluaran_part_time" class="form-control" 
                            placeholder="0" min="0" value="0" id="partTime">
                    </div>

                    <div class="laporan-field">
                        <label><i class="fas fa-money-bill-wave mr-1"></i>Gestun</label>
                        <input type="number" name="pengeluaran_gestun" class="form-control" 
                            placeholder="0" min="0" value="0" id="gestun">
                    </div>

                    <div class="laporan-field">
                        <label><i class="fas fa-ellipsis-h mr-1"></i>Lainnya</label>
                        <input type="number" name="pengeluaran_lain" class="form-control" 
                            placeholder="0" min="0" value="0" id="pengeluaranLain">
                    </div>

                    <div class="laporan-field" id="keteranganField" style="display:none;">
                        <label>Keterangan Pengeluaran Lainnya <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan_pengeluaran" class="form-control" 
                            placeholder="Jelaskan pengeluaran ini...">
                    </div>
                </div>

                <div class="laporan-field mt-3">
                    <label><i class="fas fa-university mr-1"></i>Saldo Midtrans</label>
                    <input type="number" name="saldo_midtrans" class="form-control" 
                        placeholder="0" min="0" value="0">
                    <small class="text-muted">Jumlah cash yang sudah ditransfer ke rekening bank (tidak ada cash fisik)</small>
                </div>

                <button type="button" class="btn btn-primary btn-block btn-generate-laporan text-white mt-4" id="btnGenerate">
                    <i class="fas fa-file-invoice mr-2"></i>Generate Laporan
                </button>
            </div>
        </div>
    </div>
</form>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pengeluaranLain = document.getElementById('pengeluaranLain');
        const keteranganField = document.getElementById('keteranganField');
        const btnGenerate = document.getElementById('btnGenerate');
        const form = document.getElementById('laporanForm');

        // Toggle Keterangan field
        if(pengeluaranLain) {
            pengeluaranLain.addEventListener('input', function() {
                if(this.value > 0) {
                    keteranganField.style.display = 'block';
                } else {
                    keteranganField.style.display = 'none';
                }
            });
        }

        // Konfirmasi Generate
        if(btnGenerate) {
            btnGenerate.addEventListener('click', function() {
                const partTime = document.getElementById('partTime').value || 0;
                const gestun = document.getElementById('gestun').value || 0;
                const lain = document.getElementById('pengeluaranLain').value || 0;
                const totalPengeluaran = parseInt(partTime) + parseInt(gestun) + parseInt(lain);
                
                const text = totalPengeluaran > 0 
                    ? `Total pengeluaran: Rp ${totalPengeluaran.toLocaleString('id-ID')}`
                    : 'Pastikan semua data sudah benar!';

                Swal.fire({
                    icon: 'question',
                    title: 'Generate Laporan?',
                    text: text,
                    html: '<p style="color:#64748b;">Laporan ini akan disimpan dan tidak dapat diedit kembali.</p>',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-check"></i> Ya, Generate',
                    cancelButtonText: '<i class="fas fa-times"></i> Batal',
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#94a3b8',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endpush