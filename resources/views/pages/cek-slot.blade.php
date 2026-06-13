@extends('layouts.main')

@section('title', 'Cek Ketersediaan | XPLAY Games')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cek-slot.css') }}">
@endpush

@section('content')

<section class="cek-slot-section">
    <div class="container">

        <!-- Title -->
        <div class="cek-slot-title">
            <h2>Cek Ketersediaan Unit</h2>
            <p>Status unit PlayStation secara real-time</p>
        </div>

        <!-- Lantai 1 -->
        <div class="mb-4">
            <h5 class="cek-slot-floor-title">
                <i class="fas fa-layer-group mr-2"></i> Lantai 1
            </h5>
            <div class="billing-grid">
                @foreach($lantai1 as $unit)
                @php
                    $billing = $unit->activeBilling;
                    $isPause = $billing && $billing->pause_at !== null;

                    $statusClass = 'billing-status-empty';

                    if ($billing) {
                        if ($isPause) {
                            $statusClass = 'billing-status-paused';
                        } elseif ($billing->status_sesi === 'active') {
                            $statusClass = 'billing-status-' . $billing->warna_status;
                        } else {
                            $statusClass = 'billing-status-hold';
                        }
                    }
                @endphp

                <div class="billing-unit-card {{ $statusClass }}">
                    <div class="billing-card-top">
                        <div>
                            <div class="billing-unit-name">{{ $unit->nama_unit }}</div>
                            <div class="billing-unit-type">{{ $unit->jenisUnit->tipe ?? '-' }}</div>
                        </div>

                        @if(!$billing)
                            <span class="billing-badge badge-empty">Available</span>
                        @elseif($isPause)
                            <span class="billing-badge badge-paused">Pause</span>
                        @elseif($billing->status_sesi === 'active')
                            <span class="billing-badge badge-active">Active</span>
                        @else
                            <span class="billing-badge badge-hold">Hold</span>
                        @endif
                    </div>

                    @if(!$billing)
                    <div class="billing-empty-box">
                        <i class="fas fa-gamepad"></i>
                        <p>Unit kosong dan siap digunakan</p>
                    </div>

                    @else

                    @if($billing->status_sesi === 'active')
                    <div class="billing-time-box">
                        <div class="billing-time-label">Sisa Waktu</div>
                        <div class="billing-countdown" data-sisa="{{ $billing->sisa_detik }}">
                            {{ gmdate('H:i:s', max($billing->sisa_detik ?? 0, 0)) }}
                        </div>

                        @if($billing->jam_selesai)
                        <div class="billing-finish-time">
                            <i class="fas fa-hourglass-end mr-1"></i>
                            Selesai: {{ $billing->jam_selesai->format('H:i') }}

                            @if($billing->jam_mulai && $billing->jam_selesai->toDateString() !== $billing->jam_mulai->toDateString())
                            <span class="billing-finish-next-day">(Besok)</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    @elseif($isPause)
                    <div class="billing-time-box paused">
                        <div class="billing-time-label">Sesi Pause</div>
                        <div class="billing-time-value">Waktu dihentikan sementara</div>

                        @if($billing->jam_selesai)
                        <div class="billing-finish-time">
                            <i class="fas fa-hourglass-end mr-1"></i>
                            Selesai: {{ $billing->jam_selesai->format('H:i') }}

                            @if($billing->jam_mulai && $billing->jam_selesai->toDateString() !== $billing->jam_mulai->toDateString())
                            <span class="billing-finish-next-day">(Besok)</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    @else
                    <div class="billing-time-box hold">
                        <div class="billing-time-label">Status</div>
                        <div class="billing-time-value">Menunggu start sesi</div>
                    </div>
                    @endif

                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Lantai 2 -->
        <div class="mb-4">
            <h5 class="cek-slot-floor-title">
                <i class="fas fa-building mr-2"></i> Lantai 2
            </h5>
            <div class="billing-grid">
                @foreach($lantai2 as $unit)
                @php
                    $billing = $unit->activeBilling;
                    $isPause = $billing && $billing->pause_at !== null;

                    $statusClass = 'billing-status-empty';

                    if ($billing) {
                        if ($isPause) {
                            $statusClass = 'billing-status-paused';
                        } elseif ($billing->status_sesi === 'active') {
                            $statusClass = 'billing-status-' . $billing->warna_status;
                        } else {
                            $statusClass = 'billing-status-hold';
                        }
                    }
                @endphp

                <div class="billing-unit-card {{ $statusClass }}">
                    <div class="billing-card-top">
                        <div>
                            <div class="billing-unit-name">{{ $unit->nama_unit }}</div>
                            <div class="billing-unit-type">{{ $unit->jenisUnit->tipe ?? '-' }}</div>
                        </div>

                        @if(!$billing)
                            <span class="billing-badge badge-empty">Available</span>
                        @elseif($isPause)
                            <span class="billing-badge badge-paused">Pause</span>
                        @elseif($billing->status_sesi === 'active')
                            <span class="billing-badge badge-active">Active</span>
                        @else
                            <span class="billing-badge badge-hold">Hold</span>
                        @endif
                    </div>

                    @if(!$billing)
                    <div class="billing-empty-box">
                        <i class="fas fa-gamepad"></i>
                        <p>Unit kosong dan siap digunakan</p>
                    </div>

                    @else

                    @if($billing->status_sesi === 'active')
                    <div class="billing-time-box">
                        <div class="billing-time-label">Sisa Waktu</div>
                        <div class="billing-countdown" data-sisa="{{ $billing->sisa_detik }}">
                            {{ gmdate('H:i:s', max($billing->sisa_detik ?? 0, 0)) }}
                        </div>

                        @if($billing->jam_selesai)
                        <div class="billing-finish-time">
                            <i class="fas fa-hourglass-end mr-1"></i>
                            Selesai: {{ $billing->jam_selesai->format('H:i') }}

                            @if($billing->jam_mulai && $billing->jam_selesai->toDateString() !== $billing->jam_mulai->toDateString())
                            <span class="billing-finish-next-day">(Besok)</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    @elseif($isPause)
                    <div class="billing-time-box paused">
                        <div class="billing-time-label">Sesi Pause</div>
                        <div class="billing-time-value">Waktu dihentikan sementara</div>

                        @if($billing->jam_selesai)
                        <div class="billing-finish-time">
                            <i class="fas fa-hourglass-end mr-1"></i>
                            Selesai: {{ $billing->jam_selesai->format('H:i') }}

                            @if($billing->jam_mulai && $billing->jam_selesai->toDateString() !== $billing->jam_mulai->toDateString())
                            <span class="billing-finish-next-day">(Besok)</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    @else
                    <div class="billing-time-box hold">
                        <div class="billing-time-label">Status</div>
                        <div class="billing-time-value">Menunggu start sesi</div>
                    </div>
                    @endif

                    @endif
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    // =====================
    // Web Audio Context
    // =====================
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    let alarmInterval = null;

    function playBeep(frequency = 880, duration = 0.15) {
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);

        oscillator.type = 'square';
        oscillator.frequency.setValueAtTime(frequency, audioCtx.currentTime);
        gainNode.gain.setValueAtTime(0.4, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);

        oscillator.start(audioCtx.currentTime);
        oscillator.stop(audioCtx.currentTime + duration);
    }

    function startAlarm() {
        if (alarmInterval) return;

        playBeep(880, 0.15);
        setTimeout(() => playBeep(880, 0.15), 200);
        setTimeout(() => playBeep(880, 0.15), 400);
        setTimeout(() => playBeep(880, 0.15), 600);

        alarmInterval = setInterval(() => {
            playBeep(880, 0.15);
            setTimeout(() => playBeep(880, 0.15), 200);
            setTimeout(() => playBeep(880, 0.15), 400);
            setTimeout(() => playBeep(880, 0.15), 600);
        }, 2000);
    }

    function stopAlarm() {
        if (alarmInterval) {
            clearInterval(alarmInterval);
            alarmInterval = null;
        }
    }

    function playAlarm(type = 'warning') {
        if (type === 'warning') {
            playBeep(880, 0.2);
            setTimeout(() => playBeep(880, 0.2), 350);
        } else if (type === 'ended') {
            startAlarm();
        }
    }

    // =====================
    // Auto Stop Alarm
    // =====================
    function checkAlarmShouldStop() {
        const countdowns = document.querySelectorAll('.billing-countdown');
        let anyEnded = false;

        countdowns.forEach(el => {
            const sisa = parseInt(el.getAttribute('data-sisa'));
            if (!isNaN(sisa) && sisa <= 0) {
                anyEnded = true;
            }
        });

        if (!anyEnded && alarmInterval) {
            stopAlarm();
        }
    }

    // =====================
    // Toast Notification
    // =====================
    function showToast(unitName, type = 'warning') {
        const existingToast = document.getElementById('cek-slot-toast-' + unitName.replace(/\s/g, ''));
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.id = 'cek-slot-toast-' + unitName.replace(/\s/g, '');
        toast.className = 'cek-slot-toast cek-slot-toast-' + type;

        if (type === 'warning') {
            toast.innerHTML = `
                <div class="cek-slot-toast-icon"><i class="fas fa-clock"></i></div>
                <div class="cek-slot-toast-body">
                    <div class="cek-slot-toast-title">Sisa 5 Menit!</div>
                    <div class="cek-slot-toast-msg">${unitName} — sesi hampir selesai</div>
                </div>
                <button class="cek-slot-toast-close" onclick="this.parentElement.remove()">×</button>
            `;
        } else {
            toast.innerHTML = `
                <div class="cek-slot-toast-icon"><i class="fas fa-flag-checkered"></i></div>
                <div class="cek-slot-toast-body">
                    <div class="cek-slot-toast-title">Sesi Selesai!</div>
                    <div class="cek-slot-toast-msg">${unitName} — waktu bermain telah habis</div>
                </div>
                <button class="cek-slot-toast-close" onclick="this.parentElement.remove()">×</button>
            `;
        }

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 8000);
    }

    // =====================
    // Countdown Timer
    // =====================
    const warned = {};
    const ended = {};

    function updateCountdowns() {
        document.querySelectorAll('.billing-countdown').forEach(function (el) {
            const unitName = el.closest('.billing-unit-card')
                ?.querySelector('.billing-unit-name')?.textContent?.trim() || 'Unit';

            let sisa = parseInt(el.getAttribute('data-sisa'));

            if (isNaN(sisa) || sisa <= 0) {
                el.textContent = '00:00:00';

                if (!ended[unitName]) {
                    ended[unitName] = true;
                    playAlarm('ended');
                    showToast(unitName, 'ended');
                }
                return;
            }

            // Peringatan 5 menit
            if (sisa <= 300 && !warned[unitName]) {
                warned[unitName] = true;
                playAlarm('warning');
                showToast(unitName, 'warning');
            }

            sisa--;
            el.setAttribute('data-sisa', sisa);

            const h = String(Math.floor(sisa / 3600)).padStart(2, '0');
            const m = String(Math.floor((sisa % 3600) / 60)).padStart(2, '0');
            const s = String(sisa % 60).padStart(2, '0');
            el.textContent = h + ':' + m + ':' + s;
        });

        // Cek apakah alarm perlu dihentikan
        checkAlarmShouldStop();
    }

    setInterval(updateCountdowns, 1000);

    // Reload halaman setiap 30 detik untuk sync data terbaru
    setInterval(() => {
        location.reload();
    }, 30000);
</script>
@endpush