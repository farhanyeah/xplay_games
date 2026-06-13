// Countdown Realtime
function formatTime(seconds) {
    seconds = parseInt(seconds);

    if (isNaN(seconds)) return '-';
    if (seconds < 0) seconds = 0;

    let h = Math.floor(seconds / 3600);
    let m = Math.floor((seconds % 3600) / 60);
    let s = seconds % 60;

    return String(h).padStart(2, '0') + ':' +
           String(m).padStart(2, '0') + ':' +
           String(s).padStart(2, '0');
}

function startBillingCountdown() {
    setInterval(function () {

        document.querySelectorAll('.billing-countdown').forEach(function (el) {

            let sisa = parseInt(el.dataset.sisa);

            if (isNaN(sisa)) return;

            sisa--;

            el.dataset.sisa = sisa;
            el.innerText = formatTime(sisa);

            let card = el.closest('.billing-unit-card');

            if (!card) return;

            card.classList.remove(
                'billing-status-success',
                'billing-status-warning',
                'billing-status-danger'
            );

            if (sisa <= 0) {
                card.classList.add('billing-status-danger');
            }
            else if (sisa <= 300) {
                card.classList.add('billing-status-warning');
            }
            else {
                card.classList.add('billing-status-success');
            }
        });

    }, 1000);
}

// Format Rupiah
function rupiah(angka) {

    angka = parseInt(angka || 0);

    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Tandai Lunas Via AJAX
function postAction(url, successMessage = null) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: successMessage || data.message,
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: error.message || 'Terjadi kesalahan.'
        });
    });
}

// Sweet Alert Confirm
function confirmAction(url, title, text) {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#94a3b8'
    }).then(result => {
        if (result.isConfirmed) {
            postAction(url);
        }
    });
}

// Open Modal Create Billing
function openCreateBillingModal(btn) {

    const unitId = btn.dataset.unitId;
    const unitName = btn.dataset.unitName;
    const unitType = btn.dataset.unitType;
    const jenisUnitId = btn.dataset.jenisUnitId;

    document.getElementById('formCreateBilling').reset();

    document.getElementById('create_billing_unit_id').value = unitId;

    document.getElementById('create_unit_display').value =
        unitName + ' - ' + unitType;

    loadPaketCreate(jenisUnitId);

    $('#modalCreateBilling').modal('show');
}

// Ambil Paket via AJAX
function loadPaketCreate(jenisUnitId) {
    const paketSelect = document.getElementById('create_paket_select');

    paketSelect.innerHTML = '<option value="">Memuat paket...</option>';

    console.log('Jenis Unit ID:', jenisUnitId);
    console.log('URL Paket:', getPaketHargaUrl);

    fetch(getPaketHargaUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            jenis_unit_id: jenisUnitId
        })
    })
    .then(async response => {
        const data = await response.json();

        console.log('Response Paket:', data);

        if (!response.ok) {
            throw data;
        }

        return data;
    })
    .then(data => {
        paketSelect.innerHTML = '<option value="">Pilih paket...</option>';

        if (data.paket_harga.length === 0 && data.paket_khusus.length === 0) {
            paketSelect.innerHTML = '<option value="">Paket tidak tersedia untuk unit ini</option>';
            return;
        }

        data.paket_harga.forEach(function(paket) {
            paketSelect.innerHTML += `
                <option value="harga-${paket.id}"
                        data-jam="${paket.jumlah_jam}"
                        data-harga="${paket.harga}">
                    ${paket.jumlah_jam} Jam - ${rupiah(paket.harga)}
                </option>
            `;
        });

        data.paket_khusus.forEach(function(paket) {
            paketSelect.innerHTML += `
                <option value="khusus-${paket.id}"
                        data-jam="${paket.jumlah_jam ?? 1}"
                        data-harga="${paket.harga}">
                    ${paket.nama_paket} - ${rupiah(paket.harga)}
                </option>
            `;
        });
    })
    .catch(error => {
        console.error('Error Load Paket:', error);
        paketSelect.innerHTML = '<option value="">Gagal memuat paket</option>';
    });
}

// Saat Paket Dipilih
function handlePaketChange(select) {
    const option = select.options[select.selectedIndex];

    document.getElementById('create_paket_harga_id').value = '';
    document.getElementById('create_paket_khusus_id').value = '';
    document.getElementById('create_jumlah_jam').value = '';
    document.getElementById('create_harga_display').value = '';

    if (!option || !select.value) return;

    const value = select.value;
    const jam = option.dataset.jam;
    const harga = option.dataset.harga;

    if (value.startsWith('harga-')) {
        document.getElementById('create_paket_harga_id').value = value.replace('harga-', '');
    }

    if (value.startsWith('khusus-')) {
        document.getElementById('create_paket_khusus_id').value = value.replace('khusus-', '');
    }

    document.getElementById('create_jumlah_jam').value = jam;
    document.getElementById('create_harga_display').value = rupiah(harga);
}

// Bayar Billing via Midtrans
function payBillingWithMidtrans(token, billingId, submitBtn = null) {
    if (!token || !billingId) {
        Swal.fire({
            icon: 'error',
            title: 'Token Tidak Tersedia',
            text: 'Token Midtrans tidak ditemukan.',
            confirmButtonColor: '#6366f1'
        });

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Billing';
        }

        return;
    }

    window.snap.pay(token, {
        onSuccess: function () {
            fetch('/operational/billing/' + billingId + '/konfirmasi-midtrans', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PATCH'
                }
            })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw data;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    text: data.message || 'Billing berhasil dikonfirmasi.',
                    confirmButtonColor: '#6366f1'
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Konfirmasi',
                    text: error.message || 'Pembayaran berhasil, tetapi konfirmasi billing gagal.',
                    confirmButtonColor: '#6366f1'
                }).then(() => {
                    window.location.reload();
                });
            });
        },

        onPending: function () {
            Swal.fire({
                icon: 'info',
                title: 'Pembayaran Belum Selesai',
                text: 'Billing sudah dibuat. Silakan lanjutkan pembayaran melalui tombol Bayar Sekarang.',
                confirmButtonColor: '#6366f1'
            }).then(() => {
                window.location.reload();
            });
        },

        onClose: function () {
            Swal.fire({
                icon: 'info',
                title: 'Pembayaran Belum Selesai',
                text: 'Billing sudah dibuat dalam status pending. Kamu bisa lanjut bayar melalui tombol Bayar Sekarang.',
                confirmButtonColor: '#6366f1'
            }).then(() => {
                window.location.reload();
            });
        },

        onError: function () {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Billing';
            }

            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: 'Terjadi kesalahan saat memproses pembayaran.',
                confirmButtonColor: '#6366f1'
            });
        }
    });
}

// Bayar Midtrans Extend
function payExtendBillingWithMidtrans(token, extendId, submitBtn = null) {
    if (!token || !extendId) {
        Swal.fire({
            icon: 'error',
            title: 'Token Tidak Tersedia',
            text: 'Token Midtrans untuk extend tidak ditemukan.',
            confirmButtonColor: '#6366f1'
        });

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Extend';
        }

        return;
    }

    window.snap.pay(token, {
        onSuccess: function () {
            fetch('/operational/billing/extend/' + extendId + '/konfirmasi-midtrans', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw data;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Extend Berhasil',
                    text: data.message || 'Waktu billing berhasil ditambahkan.',
                    confirmButtonColor: '#6366f1'
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Konfirmasi Extend',
                    text: error.message || 'Pembayaran berhasil, tetapi konfirmasi extend gagal.',
                    confirmButtonColor: '#6366f1'
                }).then(() => {
                    window.location.reload();
                });
            });
        },

        onPending: function () {
            Swal.fire({
                icon: 'info',
                title: 'Pembayaran Extend Pending',
                text: 'Extend sudah dibuat. Silakan lanjutkan pembayaran melalui tombol Bayar Extend.',
                confirmButtonColor: '#6366f1'
            }).then(() => {
                window.location.reload();
            });
        },

        onClose: function () {
            Swal.fire({
                icon: 'info',
                title: 'Pembayaran Extend Belum Selesai',
                text: 'Extend disimpan sebagai pending. Kamu bisa lanjut bayar melalui tombol Bayar Extend.',
                confirmButtonColor: '#6366f1'
            }).then(() => {
                window.location.reload();
            });
        },

        onError: function () {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Extend';
            }

            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Extend Gagal',
                text: 'Terjadi kesalahan saat memproses pembayaran extend.',
                confirmButtonColor: '#6366f1'
            });
        }
    });
}

// Event Listener
document.addEventListener('DOMContentLoaded', function () {

    startBillingCountdown();

    document.querySelectorAll(
        '.btn-open-create-billing'
    ).forEach(function(btn) {

        btn.addEventListener('click', function () {

            openCreateBillingModal(this);

        });

    });

    const paketSelect =
        document.getElementById('create_paket_select');

    if (paketSelect) {

        paketSelect.addEventListener('change', function () {

            handlePaketChange(this);

        });

    }

    // DOM Ready
    const formCreateBilling =
        document.getElementById('formCreateBilling');

    if (formCreateBilling) {

        formCreateBilling.addEventListener('submit', function(e) {

            e.preventDefault();

            submitCreateBilling(this);

        });

    }

    document.querySelectorAll('.btn-bayar-billing-midtrans').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const token = this.dataset.token;
            const billingId = this.dataset.id;

            payBillingWithMidtrans(token, billingId);
        });
    });

    document.querySelectorAll('.btn-open-extend-billing').forEach(btn => {
        btn.addEventListener('click', function () {
            openExtendBillingModal(this);
        });
    });

    const paketExtendSelect = document.getElementById('extend_paket_select');

    if (paketExtendSelect) {
        paketExtendSelect.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            const harga = option.dataset.harga || 0;

            document.getElementById('extend_harga_display').value = rupiah(harga);
        });
    }

    const formExtend = document.getElementById('formExtendBilling');

    if (formExtend) {
        formExtend.addEventListener('submit', function (e) {
            e.preventDefault();
            submitExtendBilling(this);
        });
    }

    document.querySelectorAll('.btn-bayar-extend-midtrans').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const token = this.dataset.token;
            const extendId = this.dataset.extendId;

            payExtendBillingWithMidtrans(token, extendId);
        });
    });
});

// Handler Submit Create Billing
function submitCreateBilling(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        $('#modalCreateBilling').modal('hide');

        // Jika metode Midtrans, buka Snap
        if (data.snap_token) {
            payBillingWithMidtrans(
                data.snap_token,
                data.billing_id || (data.billing ? data.billing.id : null),
                submitBtn
            );

            return;
        }

        // Jika cash, tetap seperti biasa
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: data.message || 'Billing berhasil dibuat.',
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        let message = error.message || 'Terjadi kesalahan saat menyimpan billing.';

        if (error.errors) {
            message = Object.values(error.errors).flat().join('\n');
        }

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: message,
            confirmButtonColor: '#6366f1'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function openExtendBillingModal(btn) {
    const billingId = btn.dataset.billingId;
    const unitName = btn.dataset.unitName;
    const unitType = btn.dataset.unitType;
    const customer = btn.dataset.customer;
    const jenisUnitId = btn.dataset.jenisUnitId;
    const action = btn.dataset.action;

    const form = document.getElementById('formExtendBilling');
    form.reset();
    form.action = action;

    document.getElementById('extend_billing_id').value = billingId;
    document.getElementById('extend_unit_display').value = unitName + ' - ' + unitType;
    document.getElementById('extend_customer_display').value = customer;
    document.getElementById('extend_harga_display').value = '';

    loadPaketExtend(jenisUnitId);

    $('#modalExtendBilling').modal('show');
}

function loadPaketExtend(jenisUnitId) {
    const paketSelect = document.getElementById('extend_paket_select');
    paketSelect.innerHTML = '<option value="">Memuat paket...</option>';

    fetch(getPaketHargaUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            jenis_unit_id: jenisUnitId
        })
    })
    .then(response => response.json())
    .then(data => {
        paketSelect.innerHTML = '<option value="">Pilih paket...</option>';

        data.paket_harga.forEach(function (paket) {
            paketSelect.innerHTML += `
                <option value="${paket.id}" data-harga="${paket.harga}">
                    ${paket.jumlah_jam} Jam - ${rupiah(paket.harga)}
                </option>
            `;
        });
    })
    .catch(() => {
        paketSelect.innerHTML = '<option value="">Gagal memuat paket</option>';
    });
}

// Extend
function submitExtendBilling(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();

        if (!response.ok) throw data;

        $('#modalExtendBilling').modal('hide');

        if (data.snap_token) {
            payExtendBillingWithMidtrans(
                data.snap_token,
                data.extend_id || (data.extend ? data.extend.id : null),
                submitBtn
            );
        return;
    }

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: data.message,
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        let message = error.message || 'Terjadi kesalahan saat extend.';

        if (error.errors) {
            message = Object.values(error.errors).flat().join('\n');
        }

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: message
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Refund
// Open Modal Refund
function openRefundBillingModal(btn) {
    const billingId = btn.dataset.billingId;
    const unitName = btn.dataset.unitName;
    const unitType = btn.dataset.unitType;
    const customer = btn.dataset.customer;
    const action = btn.dataset.action;

    const form = document.getElementById('formRefundBilling');
    form.reset();
    form.action = action;

    document.getElementById('refund_billing_id').value = billingId;
    document.getElementById('refund_unit_display').value = unitName + ' - ' + unitType;
    document.getElementById('refund_customer_display').value = customer;

    $('#modalRefundBilling').modal('show');
}

// Submit Refund via AJAX
function submitRefundBilling(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw data;

        $('#modalRefundBilling').modal('hide');

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: data.message,
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        let message = error.message || 'Terjadi kesalahan saat refund.';
        if (error.errors) message = Object.values(error.errors).flat().join('\n');

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: message
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Bind tombol dan form Refund
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-open-refund-billing').forEach(btn => {
        btn.addEventListener('click', function () {
            openRefundBillingModal(this);
        });
    });

    const formRefund = document.getElementById('formRefundBilling');
    if (formRefund) {
        formRefund.addEventListener('submit', function (e) {
            e.preventDefault();
            submitRefundBilling(this);
        });
    }
});

// Pindah Unit
// Open Modal Pindah Unit
function openPindahUnitModal(btn) {
    const billingId = btn.dataset.billingId;
    const unitName = btn.dataset.unitName;
    const unitType = btn.dataset.unitType;
    const customer = btn.dataset.customer;
    const action = btn.dataset.action;

    const form = document.getElementById('formPindahUnit');
    form.reset();
    form.action = action;

    document.getElementById('pindah_billing_id').value = billingId;
    document.getElementById('pindah_unit_display').value = unitName + ' - ' + unitType;
    document.getElementById('pindah_customer_display').value = customer;

    loadUnitTujuan(billingId);

    $('#modalPindahUnit').modal('show');
}

// Load unit tujuan via AJAX
function loadUnitTujuan(billingId) {
    const select = document.getElementById('pindah_unit_select');
    select.innerHTML = '<option value="">Memuat unit...</option>';

    fetch(`/operational/billing/get-available-units/${billingId}`, {
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {
        select.innerHTML = '<option value="">Pilih unit tersedia...</option>';
        data.forEach(unit => {
            select.innerHTML += `<option value="${unit.id}">${unit.nama_unit} - ${unit.jenis_tipe}</option>`;
        });
    })
    .catch(() => select.innerHTML = '<option value="">Gagal memuat unit</option>');
}

// Submit pindah unit via AJAX
function submitPindahUnit(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: new FormData(form)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw data;

        $('#modalPindahUnit').modal('hide');

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: data.message,
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        let message = error.message || 'Terjadi kesalahan saat pindah unit.';
        if (error.errors) message = Object.values(error.errors).flat().join('\n');

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: message
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Bind tombol dan form pindah unit
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-open-pindah-billing').forEach(btn => {
        btn.addEventListener('click', function () {
            openPindahUnitModal(this);
        });
    });

    const formPindah = document.getElementById('formPindahUnit');
    if (formPindah) {
        formPindah.addEventListener('submit', function(e) {
            e.preventDefault();
            submitPindahUnit(this);
        });
    }
});