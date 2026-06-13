@extends('layouts.main')

@section('title', 'Koleksi Game | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/list-games.css') }}">
@endpush

@section('content')

<!-- List Game Page -->
<section class="list-game-page">

    <div class="container">

        <!-- Title -->
        <div class="game-title reveal">

            <div class="section-label center-label">
                <p>KOLEKSI GAME</p>
            </div>

            <h1>Koleksi Game XPLAY</h1>
            <p>
                Nikmati berbagai pilihan game seru
                yang tersedia di XPLAY Games
            </p>

        </div>

        <!-- Game Wrapper -->
        <div class="game-wrapper">

            <!-- PS4 -->
            <div class="game-card reveal">

                <h2>PS4 & PS4 Pro</h2>

                <div class="game-list-wrapper">

                    <div class="game-list">

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/a-way-out.jpg') }}" 
                            alt="A Way Out">
                            <p>A Way Out</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/assasin-creed-valhalla.jpg') }}" 
                            alt="Assassin Creed Valhalla">
                            <p>Assassin Creed Valhalla</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/cod-modern-warfare-remastered.jpg') }}" 
                            alt="Call of Duty : Modern Warfare Remastered">
                            <p>Call of Duty : Modern Warfare Remastered</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/ctr-nitro-fueled.jpg') }}" 
                            alt="Crash Team Racing Nitro- Fueled">
                            <p>Crash Team Racing Nitro- Fueled</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/fc26.jpg') }}" 
                            alt="EA Sports FC 26">
                            <p>EA Sports FC 26</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/efootball.jpg') }}" 
                            alt="eFootball PES 2026">
                            <p>eFootball PES 2026</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/god-of-war.jpg') }}" 
                            alt="God of War Ragnarok">
                            <p>God of War Ragnarok</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/gta-v.jpg') }}" 
                            alt="Grand Theft Auto V">
                            <p>Grand Theft Auto V</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/grand-turismo-7.jpg') }}" alt="Gran Turismo 7">
                            <p>Grand Turismo 7</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/guns-gore-cannoli.jpg') }}" 
                            alt="Guns Gore and Cannoli">
                            <p>Guns Gore and Cannoli</p>
                        </div>

                    </div>

                </div>

            </div>

            <!-- PS5 -->
            <div class="game-card reveal">

                <h2>PS5, VIP & VVIP</h2>

                <div class="game-list-wrapper">

                    <div class="game-list">

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/a-way-out.jpg') }}" 
                            alt="A Way Out">
                            <p>A Way Out</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/assasin-creed-valhalla.jpg') }}" 
                            alt="Assassin Creed Valhalla">
                            <p>Assassin Creed Valhalla</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/cod-modern-warfare-remastered.jpg') }}" 
                            alt="Call of Duty : Modern Warfare Remastered">
                            <p>Call of Duty : Modern Warfare Remastered</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/ctr-nitro-fueled.jpg') }}" 
                            alt="Crash Team Racing Nitro- Fueled">
                            <p>Crash Team Racing Nitro- Fueled</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/fc26.jpg') }}" 
                            alt="EA Sports FC 26">
                            <p>EA Sports FC 26</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/efootball.jpg') }}"
                            alt="eFootball PES 2026">
                            <p>eFootball PES 2026</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/god-of-war.jpg') }}" 
                            alt="God of War Ragnarok">
                            <p>God of War Ragnarok</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/gta-v.jpg') }}" 
                            alt="Grand Theft Auto V">
                            <p>Grand Theft Auto V</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/grand-turismo-7.jpg') }}" alt="Grand Turismo 7">
                            <p>Grand Turismo 7</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/guns-gore-cannoli.jpg') }}" 
                            alt="Guns Gore and Cannoli">
                            <p>Guns Gore and Cannoli</p>
                        </div>

                    </div>

                </div>

            </div>

           <!-- Nintendo -->
            <div class="game-card reveal">

                <h2>Nintendo</h2>

                <div class="game-list-wrapper">

                    <div class="game-list">

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/a-way-out.jpg') }}" 
                            alt="A Way Out">
                            <p>A Way Out</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/assasin-creed-valhalla.jpg') }}" 
                            alt="Assassin Creed Valhalla">
                            <p>Assassin Creed Valhalla</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/cod-modern-warfare-remastered.jpg') }}" 
                            alt="Call of Duty : Modern Warfare Remastered">
                            <p>Call of Duty : Modern Warfare Remastered</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/ctr-nitro-fueled.jpg') }}" 
                            alt="Crash Team Racing Nitro- Fueled">
                            <p>Crash Team Racing Nitro- Fueled</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/fc26.jpg') }}" 
                            alt="EA Sports FC 26">
                            <p>EA Sports FC 26</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/efootball.jpg') }}"
                            alt="eFootball PES 2026">
                            <p>eFootball PES 2026</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/god-of-war.jpg') }}" 
                            alt="God of War Ragnarok">
                            <p>God of War Ragnarok</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/gta-v.jpg') }}" 
                            alt="Grand Theft Auto V">
                            <p>Grand Theft Auto V</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/grand-turismo-7.jpg') }}" alt="Grand Turismo 7">
                            <p>Grand Turismo 7</p>
                        </div>

                        <div class="game-item">
                            <img src="{{ asset('images/list_games/guns-gore-cannoli.jpg') }}" 
                            alt="Guns Gore and Cannoli">
                            <p>Guns Gore and Cannoli</p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection