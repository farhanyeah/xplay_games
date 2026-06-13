<!-- Navbar -->
<nav class="navbar">
    <div class="container nav-wrapper">

        <!-- Logo -->
        <div class="logo">
            <a href="{{ url('/') }}" class="logo-link">
                <img src="{{ asset('images/main/xplay_games.jpg') }}" alt="XPLAY Logo" class="logo-img">
                <h1>XPLAY GAMES</h1>
            </a>
        </div>

        <!-- Menu -->
        <ul class="nav-menu" id="nav-menu">

            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    Beranda
                </a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle {{ request()->is('promo') 
                || request()->is('tipe-ps') 
                || request()->is('list-game') ? 'active' : '' }}">Layanan
                </a>

                <!-- Dropdown Menu Layanan -->
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ url('/promo') }}">Promo</a>
                    </li>
                    <li>
                        <a href="{{ url('/tipe-ps') }}">Tipe PlayStation</a>
                    </li>
                    <li>
                        <a href="{{ url('/list-game') }}">Daftar Game</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ url('/info') }}" class="{{ request()->is('info') ? 'active' : '' }}">Info</a>
            </li>

            <li>
                <a href="{{ url('/sewa') }}" class="{{ request()->is('sewa') ? 'active' : '' }}">Sewa</a>
            </li>

            <li>
                <a href="{{ url('/booking') }}" class="{{ request()->is('booking') ? 'active' : ''}}">Booking</a>
            </li>

            <li>
                @auth
                <!-- Sudah Login -->
                <div class="nav-user">

                    <button class="nav-user-btn" id="user-toggle">
                        Halo, {{ Auth::user()->name }}
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>

                    <ul class="nav-user-dropdown" id="user-dropdown">

                        @if(Auth::user()->role === 'staf' || Auth::user()->role === 'owner')
                        <li>
                            <button onclick="window.location='{{ route('operational.dashboard') }}'">
                                <i class="fa-solid fa-gauge"></i>
                                Dashboard
                            </button>
                        </li>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="btn-logout-confirm">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                Keluar
                            </button>
                        </form>
                    </ul>
                </div>

                @else
                <!-- belum login -->
                <a href="{{ url('/login') }}">Masuk</a>
                @endauth
            </li>
        </ul>

        <!-- Mobile Button -->
        <div class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
        
    </div>
</nav>