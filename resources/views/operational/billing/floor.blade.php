<div class="card ops-card mb-4">
    <div class="card-header ops-card-header justify-content-between">
        <div class="d-flex align-items-center">
            <i class="{{ $icon }} mr-2 ops-header-icon"></i>
            <h6 class="m-0 font-weight-bold ops-header-title">{{ $title }}</h6>
        </div>

        <span class="ops-badge-total">
            {{ $units->count() }} Unit
        </span>
    </div>

    <div class="card-body billing-floor-body">
        <div class="billing-grid">
            @foreach($units as $unit)
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

                <button type="button" class="btn ops-btn-aksi-full btn-detail btn-open-create-billing"
                    data-unit-id="{{ $unit->id }}" data-unit-name="{{ $unit->nama_unit }}"
                    data-unit-type="{{ $unit->jenisUnit->tipe ?? '-' }}"
                    data-jenis-unit-id="{{ $unit->jenis_unit_id }}">
                    <i class="fas fa-plus mr-1"></i> Buat Billing
                </button>
                @else
                <div class="billing-info">
                    <div class="billing-customer">{{ $billing->nama_customer }}</div>

                    <div class="billing-meta">
                        <span>
                            <i class="fas fa-clock mr-1"></i>
                            {{ $billing->jumlah_jam }} Jam
                        </span>

                        <span>
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Rp {{ number_format($billing->harga_final, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="billing-payment">
                        <span class="billing-pay-method">{{ strtoupper($billing->metode_bayar) }}</span>

                        @if($billing->status_bayar === 'paid')
                        <span class="billing-pay-status paid">Lunas</span>
                        @else
                        <span class="billing-pay-status pending">Pending</span>
                        @endif
                    </div>

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

                            @if($billing->jam_mulai && $billing->jam_selesai->toDateString() !==
                            $billing->jam_mulai->toDateString())
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

                            @if($billing->jam_mulai && $billing->jam_selesai->toDateString() !==
                            $billing->jam_mulai->toDateString())
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
                </div>

                @php
                $pendingExtend = $billing->extends
                ->where('metode_bayar', 'midtrans')
                ->where('status_bayar', 'pending')
                ->first();
                @endphp

                <div class="billing-action-grid">
                    @if($billing->status_bayar !== 'paid')

                    @php
                    $midtransToken = $billing->midtrans_token ?? $billing->snap_token ?? null;
                    @endphp

                    @if($billing->metode_bayar === 'midtrans' && $billing->midtrans_token)
                    <button type="button" class="ops-btn-aksi btn-secondary btn-bayar-billing-midtrans"
                        title="Bayar Sekarang" data-id="{{ $billing->id }}" data-token="{{ $billing->midtrans_token }}">
                        <i class="fas fa-credit-card"></i>
                    </button>
                    @else
                    <button type="button" class="ops-btn-aksi btn-complete" title="Tandai Lunas"
                        onclick="confirmAction('{{ route('operational.billing.paid', $billing->id) }}', 'Tandai Lunas?', 'Pembayaran cash akan ditandai lunas.')">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif

                    <button class="ops-btn-aksi btn-hapus" title="Batalkan Billing"
                        onclick="confirmAction('{{ route('operational.billing.cancel', $billing->id) }}', 'Batalkan Billing?', 'Unit akan dilepas dari hold.')">
                        <i class="fas fa-times"></i>
                    </button>
                    @else

                    @if($pendingExtend && $pendingExtend->midtrans_token)
                    <button type="button" class="ops-btn-aksi btn-secondary btn-bayar-extend-midtrans"
                        title="Bayar Extend Pending" data-extend-id="{{ $pendingExtend->id }}"
                        data-token="{{ $pendingExtend->midtrans_token }}">
                        <i class="fas fa-credit-card"></i>
                    </button>
                    @endif

                    @if($billing->status_sesi === 'available' && !$isPause)
                    <button class="ops-btn-aksi btn-secondary"
                        onclick="confirmAction('{{ route('operational.billing.start', $billing->id) }}', 'Mulai Sesi?', 'Waktu billing akan mulai berjalan.')">
                        <i class="fas fa-play"></i>
                    </button>
                    @endif

                    @if($billing->status_sesi === 'active')
                    <button class="ops-btn-aksi btn-secondary" title="Pause Sesi"
                        onclick="confirmAction('{{ route('operational.billing.pause', $billing->id) }}', 'Pause Sesi?', 'Waktu billing akan dihentikan sementara.')">
                        <i class="fas fa-pause"></i>
                    </button>
                    @endif

                    @if($isPause)
                    <button class="ops-btn-aksi btn-complete" title="Lanjutkan Sesi"
                        onclick="confirmAction('{{ route('operational.billing.resume', $billing->id) }}', 'Resume Sesi?', 'Waktu billing akan berjalan kembali.')">
                        <i class="fas fa-play"></i>
                    </button>
                    @endif

                    <button type="button" class="ops-btn-aksi btn-warning btn-open-extend-billing" title="Extend"
                        data-billing-id="{{ $billing->id }}" data-unit-name="{{ $unit->nama_unit }}"
                        data-unit-type="{{ $unit->jenisUnit->tipe ?? '-' }}"
                        data-customer="{{ $billing->nama_customer }}" data-jenis-unit-id="{{ $unit->jenis_unit_id }}"
                        data-action="{{ route('operational.billing.extend', $billing->id) }}">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" class="ops-btn-aksi btn-detail btn-open-pindah-billing" title="Pindah Unit"
                        data-billing-id="{{ $billing->id }}" data-unit-name="{{ $unit->nama_unit }}"
                        data-unit-type="{{ $unit->jenisUnit->tipe ?? '-' }}"
                        data-customer="{{ $billing->nama_customer }}"
                        data-action="{{ route('operational.billing.pindahUnit', $billing->id) }}">
                        <i class="fas fa-exchange-alt"></i>
                    </button>

                    <button type="button" class="ops-btn-aksi btn-hapus btn-open-refund-billing" title="Refund"
                        data-billing-id="{{ $billing->id }}" data-unit-name="{{ $unit->nama_unit }}"
                        data-unit-type="{{ $unit->jenisUnit->tipe ?? '-' }}"
                        data-customer="{{ $billing->nama_customer }}"
                        data-action="{{ route('operational.billing.refund', $billing->id) }}">
                        <i class="fas fa-undo"></i>
                    </button>

                    <button class="ops-btn-aksi btn-complete" title="Selesaikan Sesi"
                        onclick="confirmAction('{{ route('operational.billing.complete', $billing->id) }}', 'Selesaikan Sesi?', 'Billing akan dipindahkan ke histori.')">
                        <i class="fas fa-flag-checkered"></i>
                    </button>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>