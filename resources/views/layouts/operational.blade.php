<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Operasional | XPLAY Games')</title>

    <link rel="icon" href="{{ asset('images/main/xplay.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- SB Admin 2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css"
        rel="stylesheet">

    <!-- Custom Operational CSS -->
    <link rel="stylesheet" href="{{ asset('css/operational.css') }}">

    @stack('styles')

</head>

<body id="page-top">

    <!-- Alert Login Success -->
    @if(session('login'))
    <div class="alert-bottom-left">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('login') }}
    </div>
    @endif

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ route('operational.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('images/main/xplay.png') }}" alt="XPLAY">
                </div>
                <div class="sidebar-brand-text mx-3">XPLAY</div>
            </a>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu</div>

            <!-- Billing -->
            <li class="nav-item {{ request()->routeIs('operational.billing') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.billing.index') }}">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                    <span>Billing</span>
                </a>
            </li>

            <!-- Data Sewa -->
            <li class="nav-item {{ request()->routeIs('operational.sewa*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.sewa.index') }}">
                    <i class="fas fa-fw fa-gamepad"></i>
                    <span>Data Sewa</span>
                </a>
            </li>

            <!-- Data Booking -->
            <li class="nav-item {{ request()->routeIs('operational.booking*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.booking.index') }}">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Data Booking</span>
                </a>
            </li>

            <!-- Penjualan -->
            <li class="nav-item {{ request()->routeIs('operational.penjualan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.penjualan') }}">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Penjualan</span>
                </a>
            </li>

            <!-- Laporan -->
            <li class="nav-item {{ request()->routeIs('operational.laporan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.laporan') }}">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <!-- Maintenance -->
            <li class="nav-item {{ request()->routeIs('operational.maintenance') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.maintenance.index') }}">
                    <i class="fas fa-fw fa-tools"></i>
                    <span>Maintenance</span>
                </a>
            </li>

            <!-- Checksheet -->
            @if(Auth::user()->role === 'owner')
            <li class="nav-item {{ request()->routeIs('operational.checksheet.riwayat') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.checksheet.riwayat') }}">
                    <i class="fas fa-fw fa-clipboard-check"></i>
                    <span>Checksheet</span>
                </a>
            </li>
            @else
            <li class="nav-item {{ request()->routeIs('operational.checksheet') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.checksheet') }}">
                    <i class="fas fa-fw fa-clipboard-check"></i>
                    <span>Checksheet</span>
                </a>
            </li>
            @endif

            @if(Auth::user()->role === 'owner')

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Owner</div>

            <!-- Kelola Staf -->
            <li class="nav-item {{ request()->routeIs('operational.kelola-staf') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.kelola-staf') }}">
                    <i class="fas fa-fw fa-users-cog"></i>
                    <span>Kelola Staf</span>
                </a>
            </li>

            <!-- Kelola Harga -->
            <li class="nav-item {{ request()->routeIs('operational.kelola-harga') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.kelola-harga') }}">
                    <i class="fas fa-fw fa-tags"></i>
                    <span>Kelola Harga</span>
                </a>
            </li>
            
            <!-- Kelola Stok -->
            <li class="nav-item {{ request()->routeIs('operational.stok') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.stok') }}">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Kelola Stok</span>
                </a>
            </li>

            <!-- Kelola Checksheet -->
            <li class="nav-item {{ request()->routeIs('operational.checksheet.manage') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('operational.checksheet.manage') }}">
                    <i class="fas fa-cog"></i>
                    <span>Kelola Checksheet</span>
                </a>
            </li>

            @endif

            <hr class="sidebar-divider">

            <!-- Back to Beranda -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-fw fa-arrow-left"></i>
                    <span>Back to Beranda</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <!-- Toggle Sidebar -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-light topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Mobile) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Hari, Tanggal & Waktu -->
                    <div class="d-none d-lg-inline mr-auto ml-4">
                        <span id="datetime"></span>
                    </div>

                    <!-- Topbar Kanan -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->name }}
                                    <span class="badge badge-primary ml-1">{{ ucfirst(Auth::user()->role) }}</span>
                                </span>
                                <i class="fas fa-user-circle fa-fw fa-lg"></i>
                            </a>

                            <!-- Dropdown User -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <button type="button" class="dropdown-item btn-logout-confirm">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </button>
                            </div>
                        </li>
                    </ul>

                </nav>
                <!-- End Topbar -->

                <!-- Main Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; XPLAY Games {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Wrapper -->

    <!-- Bootstrap & SB Admin JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        function updateDateTime() {
            const now = new Date();

            const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];

            const namaHari = hari[now.getDay()];
            const tanggal = now.getDate();
            const namaBulan = bulan[now.getMonth()];
            const tahun = now.getFullYear();

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('datetime').textContent =
                `${namaHari}, ${tanggal} ${namaBulan} ${tahun} — ${jam}:${menit}:${detik}`;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>

    @push('scripts')
    <!-- Alert Bottom Left - Auto Hide -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertBottomLeft = document.querySelector('.alert-bottom-left');
            if (alertBottomLeft) {
                setTimeout(() => {
                    alertBottomLeft.style.left = '30px';
                    alertBottomLeft.style.opacity = '1';
                }, 100);

                setTimeout(() => {
                    alertBottomLeft.style.left = '-300px';
                    alertBottomLeft.style.opacity = '0';
                    setTimeout(() => alertBottomLeft.remove(), 500);
                }, 4000);
            }
        });
    </script>
    @endpush

    @push('scripts')
    <!-- Sweet Alert Logout Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutButtons = document.querySelectorAll('.btn-logout-confirm');

            logoutButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'question',
                        title: 'Konfirmasi Logout',
                        text: 'Apakah Anda yakin ingin logout?',
                        showCancelButton: true,
                        confirmButtonText: 'Logout',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#94a3b8'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("logout") }}';

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush

    @stack('scripts')

</body>

</html>