/* =========================
   GLOBAL FLAG (ANTI DOUBLE SUBMIT)
========================= */
let isSubmitting = false;

/* =========================
   MOBILE NAVBAR TOGGLE
========================= */

document.addEventListener('DOMContentLoaded', function () {

    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    if(menuToggle && navMenu) {

    // Toggle Mobile Menu
    menuToggle.addEventListener('click', function () {
        navMenu.classList.toggle('active');

        // Toggle icon: bars <-> X
        const icon = menuToggle.querySelector('i');
        if (navMenu.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            menuToggle.classList.add('active');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            menuToggle.classList.remove('active');
        }
    });

    // Reset Menu On Desktop (dan icon juga reset)
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            navMenu.classList.remove('active');

            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                menuToggle.classList.remove('active');
            }
        }
    });
    }

});


// =========================================
// NAVBAR SCROLL EFFECT
// =========================================

const navbar = document.querySelector('.navbar');

if (navbar) {

window.addEventListener('scroll', function () {

    if (window.scrollY > 50) {

        navbar.classList.add('scrolled');

    } else {

        navbar.classList.remove('scrolled');

    }

});
}


// =========================================
// SCROLL REVEAL ANIMATION
// =========================================

function revealOnScroll() {

    const reveals = document.querySelectorAll('.reveal');

    reveals.forEach(function (element) {

        const windowHeight = window.innerHeight;
        const revealTop = element.getBoundingClientRect().top;

        const revealPoint = 120;

        if (revealTop < windowHeight - revealPoint) {

            element.classList.add('active');

        }

    });

}

// Run On Scroll
window.addEventListener('scroll', revealOnScroll);

// Run First Load
revealOnScroll();

// =========================================
// DOCUMENTATION CAROUSEL
// =========================================

const slides = document.querySelectorAll('.documentation-slide');
const dots = document.querySelectorAll('.documentation-dots .dot');

let currentSlide = 0;

// ===== SHOW SLIDE =====
function showSlide(index) {

    slides.forEach(slide => {
        slide.classList.remove('active');
    });

    dots.forEach(dot => {
        dot.classList.remove('active');
    });

    slides[index].classList.add('active');
    dots[index].classList.add('active');

    currentSlide = index;
}

// ===== NEXT SLIDE =====
function nextSlide() {

    currentSlide++;

    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }

    showSlide(currentSlide);
}

// ===== AUTO SLIDE =====
setInterval(nextSlide, 4000);

// ===== DOT CLICK =====
dots.forEach((dot, index) => {

    dot.addEventListener('click', function () {

        showSlide(index);

    });

});

// ===== DROPDOWN MOBILE =====
const dropdown = document.querySelector('.dropdown');
const dropdownToggle = document.querySelector('.dropdown-toggle');

if (dropdown && dropdownToggle) {

dropdownToggle.addEventListener('click', function (e) {

    if (window.innerWidth <= 768) {
        e.preventDefault();

        dropdown.classList.toggle('active');
    }

});
}

// Always Start From Top
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

window.scrollTo(0, 0);

// Nav User Dropdown
const userToggle = document.getElementById('user-toggle');
const userDropdown = document.getElementById('user-dropdown');

if (userToggle && userDropdown) {
    userToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
        userToggle.classList.toggle('active');
    });

    // Klik di luar dropdown = tutup
    document.addEventListener('click', function () {
        userDropdown.classList.remove('show');
        userToggle.classList.remove('active');
    });
}

/* =========================
   DATE PICKER MIN DATE
========================= */

document.addEventListener('DOMContentLoaded', function () {

    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    if (!startDate || !endDate) return;

    const today = new Date();
    const todayStr = today.toISOString().slice(0, 16);

    startDate.min = todayStr;
    endDate.min = todayStr;
});

/* =========================
   GUARANTEE FIELD
========================= */

const guaranteeSelect = document.getElementById('guaranteeSelect');
const guaranteeOtherGroup = document.getElementById('guaranteeOtherGroup');
const guaranteeOther = document.getElementById('guaranteeOther');

if (guaranteeSelect) {
    guaranteeSelect.addEventListener('change', function () {
        if (this.value === 'Lainnya') {
            guaranteeOtherGroup.style.display = 'block';
            guaranteeOther.required = true;
        } else {
            guaranteeOtherGroup.style.display = 'none';
            guaranteeOther.required = false;
        }
    });
}


/* =========================
   HARGA KALKULASI SEWA PS
========================= */

const paketSelect = document.getElementById('paketSelect');
const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');
const hargaDisplay = document.getElementById('hargaDisplay');
const totalHargaInput = document.getElementById('totalHarga');
const jaminanDisplay = document.getElementById('jaminanDisplay');
const hargaJaminanInput = document.getElementById('hargaJaminan');
const sewaDisplay = document.getElementById('sewaDisplay');
const hargaSewaInput = document.getElementById('hargaSewa');
const durasiCustomGroup = document.getElementById('durasiCustomGroup');
const durasiCustomInput = document.getElementById('durasiCustom');

function calculatePrice() {
    if (!paketSelect || !startDateInput) return;

    const unitSelect = document.getElementById('unitSelect');

    // CEK: Unit wajib pilih dulu
    if (!unitSelect || !unitSelect.value) {
        hargaDisplay.innerText = 'Rp ...';
        jaminanDisplay.innerText = 'Rp ...';
        sewaDisplay.innerText = 'Rp ...';
        totalHargaInput.value = '';
        hargaJaminanInput.value = '';
        hargaSewaInput.value = '';
        return;
    }

    // Ambil harga jaminan dari unit yang dipilih
    const selectedUnit = unitSelect.options[unitSelect.selectedIndex];
    const hargaJaminan = parseInt(selectedUnit.getAttribute('data-jaminan')) || 0;

    const selectedPaket = paketSelect.options[paketSelect.selectedIndex];
    const paketValue = selectedPaket.value;

    // Tampilkan harga jaminan begitu unit dipilih
    if (hargaJaminan > 0) {
        jaminanDisplay.innerText = 'Rp ' + hargaJaminan.toLocaleString('id-ID');
        hargaJaminanInput.value = hargaJaminan;
    }

    // Kalau belum pilih paket atau belum isi tanggal mulai, stop
    if (!paketValue || !startDateInput.value) {
        sewaDisplay.innerText = 'Rp ...';
        hargaDisplay.innerText = 'Rp ...';
        hargaSewaInput.value = '';
        totalHargaInput.value = '';
        return;
    }

    let hargaSewa = 0;
    let totalHari = 0;

    if (paketValue === 'lainnya') {
        // Durasi custom — harga 1 hari × jumlah hari
        const jumlahHari = parseInt(durasiCustomInput.value);

        if (!jumlahHari || jumlahHari < 1) {
            sewaDisplay.innerText = 'Rp ...';
            hargaDisplay.innerText = 'Rp ...';
            hargaSewaInput.value = '';
            totalHargaInput.value = '';
            return;
        }

        // Cari harga 1 hari untuk jenis unit yang dipilih
        const jenisUnitId = selectedUnit.getAttribute('data-jenis');
        let harga1Hari = 0;

        Array.from(paketSelect.options).forEach(opt => {
            if (
                opt.getAttribute('data-jenis') === jenisUnitId &&
                parseInt(opt.getAttribute('data-hari')) === 1
            ) {
                harga1Hari = parseInt(opt.getAttribute('data-harga'));
            }
        });

        hargaSewa = harga1Hari * jumlahHari;
        totalHari = jumlahHari;

    } else {
        // Paket normal
        hargaSewa = parseInt(selectedPaket.getAttribute('data-harga'));
        totalHari = parseInt(selectedPaket.getAttribute('data-hari'));
    }

    // Hitung End Date otomatis
    const start = new Date(startDateInput.value);
    const end = new Date(start);
    end.setDate(end.getDate() + totalHari);

    // Format manual tanpa timezone issue
    const year = end.getFullYear();
    const month = String(end.getMonth() + 1).padStart(2, '0');
    const day = String(end.getDate()).padStart(2, '0');
    const hours = String(end.getHours()).padStart(2, '0');
    const minutes = String(end.getMinutes()).padStart(2, '0');

    endDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
    endDateInput.min = endDateInput.value;

    // Total harga = harga sewa + harga jaminan
    const totalHarga = hargaSewa + hargaJaminan;

    sewaDisplay.innerText = 'Rp ' + hargaSewa.toLocaleString('id-ID');
    hargaSewaInput.value = hargaSewa;

    hargaDisplay.innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
    totalHargaInput.value = totalHarga;
}

// Event listener durasi custom
if (durasiCustomInput) {
    durasiCustomInput.addEventListener('input', calculatePrice);
}

if (paketSelect) {
    paketSelect.addEventListener('change', function () {
        if (this.value === 'lainnya') {
            durasiCustomGroup.style.display = 'block';
            durasiCustomInput.required = true;
        } else {
            durasiCustomGroup.style.display = 'none';
            durasiCustomInput.required = false;
            durasiCustomInput.value = '';
        }
        calculatePrice();
    });
}

if (startDateInput) {
    startDateInput.addEventListener('change', calculatePrice);
}

/* =========================
   FILTER PAKET BY UNIT
========================= */

const unitSelect = document.getElementById('unitSelect');

if (unitSelect && paketSelect) {
    unitSelect.addEventListener('change', function () {
        const selectedUnit = unitSelect.options[unitSelect.selectedIndex];
        const jenisUnitId = selectedUnit.getAttribute('data-jenis');
        const hargaJaminan = parseInt(selectedUnit.getAttribute('data-jaminan')) || 0;

        // Filter paket sesuai jenis unit, tetap tampilkan opsi "Lainnya"
        Array.from(paketSelect.options).forEach(option => {
            if (option.value === '' || option.value === 'lainnya') return;

            const paketJenis = option.getAttribute('data-jenis');

            if (jenisUnitId && paketJenis !== jenisUnitId) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = '';
                option.disabled = false;
            }
        });

        // Reset paket & durasi custom
        paketSelect.value = '';
        durasiCustomGroup.style.display = 'none';
        durasiCustomInput.value = '';
        durasiCustomInput.required = false;

        // Tampilkan harga jaminan langsung
        if (hargaJaminan > 0) {
            jaminanDisplay.innerText = 'Rp ' + hargaJaminan.toLocaleString('id-ID');
            hargaJaminanInput.value = hargaJaminan;
        } else {
            jaminanDisplay.innerText = 'Rp ...';
            hargaJaminanInput.value = '';
        }

        // Reset harga sewa & total
        sewaDisplay.innerText = 'Rp ...';
        hargaSewaInput.value = '';
        hargaDisplay.innerText = 'Rp ...';
        totalHargaInput.value = '';

        calculatePrice();
    });
}

/* =========================
   RENTAL VALIDATION
========================= */

const rentalForm = document.getElementById('rentalForm');
const openConfirmBtn = document.getElementById('openConfirm');

if (rentalForm && openConfirmBtn) {
    openConfirmBtn.addEventListener('click', function (e) {
        e.preventDefault();

        let isValid = true;
        let firstErrorField = null;

        // Clear semua error dulu
        rentalForm.querySelectorAll('.error-message').forEach(span => {
            span.textContent = '';
            span.style.display = 'none';
        });
        rentalForm.querySelectorAll('.input-error').forEach(field => {
            field.classList.remove('input-error');
        });

        // Validasi field required biasa
        const requiredFields = rentalForm.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            // Skip durasi_custom jika paket bukan "lainnya"
            if (field.name === 'durasi_custom' && paketSelect.value !== 'lainnya') return;

            const errorSpan = rentalForm.querySelector(`.error-message[data-input="${field.name}"]`);
            const value = field.value ? field.value.trim() : '';

            if (!value) {
                isValid = false;
                field.classList.add('input-error');

                if (errorSpan) {
                    errorSpan.textContent = getErrorMessage(field);
                    errorSpan.style.display = 'block';
                }

                if (!firstErrorField)
                    firstErrorField = field;

            } else if (field.id === 'no_hp') {
                const hpRegex = /^08[0-9]+$/;

                if (!hpRegex.test(value)) {
                    isValid = false;
                    field.classList.add('input-error');

                    if (errorSpan) {
                        errorSpan.textContent = 'Format nomor HP tidak valid. Contoh: 08xxxxx';
                        errorSpan.style.display = 'block';
                    }

                    if (!firstErrorField)
                        firstErrorField = field;
                } else if (value.length < 10) {
                    // Validasi panjang minimal 10 digit
                    isValid = false;
                    field.classList.add('input-error');

                    if (errorSpan) {
                        errorSpan.textContent = 'Nomor HP terlalu pendek. Minimal 10 digit.';
                        errorSpan.style.display = 'block';
                    }

                    if (!firstErrorField)
                        firstErrorField = field;
                } else if (value.length > 13) {
                    // Validasi panjang maksimal 15 digit
                    isValid = false;
                    field.classList.add('input-error');

                    if (errorSpan) {
                        errorSpan.textContent = 'Nomor HP terlalu panjang. Minimal 13 digit.';
                        errorSpan.style.display = 'block';
                    }

                    if (!firstErrorField)
                        firstErrorField = field;
                }
            }
        });

        // Validasi paket_id khusus
        const paketErrorSpan = rentalForm.querySelector('.error-message[data-input="paket_id"]');
        if (!paketSelect.value) {
            isValid = false;
            paketSelect.classList.add('input-error');
            if (paketErrorSpan) {
                paketErrorSpan.textContent = 'Paket durasi wajib dipilih.';
                paketErrorSpan.style.display = 'block';
            }
            if (!firstErrorField) firstErrorField = paketSelect;
        }

        // Validasi durasi_custom jika pilih lainnya
        if (paketSelect.value === 'lainnya') {
            const durasiVal = parseInt(durasiCustomInput.value);
            const durasiErrorSpan = rentalForm.querySelector('.error-message[data-input="durasi_custom"]');

            if (!durasiVal || durasiVal < 1) {
                isValid = false;
                durasiCustomInput.classList.add('input-error');

                if (durasiErrorSpan) {
                    durasiErrorSpan.textContent = 'Jumlah hari wajib diisi minimal 1 hari.';
                    durasiErrorSpan.style.display = 'block';
                }

                if (!firstErrorField) firstErrorField = durasiCustomInput;
            }
        }

        // Validasi harga sudah terhitung
        if (!totalHargaInput.value) {
            isValid = false;
            if (!firstErrorField) firstErrorField = paketSelect;
        } else {
            hargaDisplay.style.color = '';
        }

        if (firstErrorField) {
            firstErrorField.focus();
            e.stopImmediatePropagation();
            return;
        }

        if (isValid) {
            fillConfirmModal();
            const confirmModal = document.getElementById('confirmModal');
            if (confirmModal) {
                confirmModal.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
        }
    });
}

function getErrorMessage(field) {
    const messages = {
        'no_hp': 'Nomor HP tidak boleh kosong.',
        'unit_id': 'Unit PlayStation wajib dipilih.',
        'paket_id': 'Paket durasi wajib dipilih.',
        'durasi_custom': 'Jumlah hari wajib diisi minimal 1 hari.',
        'pembayaran': 'Metode pembayaran wajib dipilih.',
        'tanggal_mulai': 'Tanggal mulai wajib diisi.',
        'tanggal_selesai': 'Tanggal selesai wajib diisi.',
        'guarantee_type': 'Jenis jaminan wajib dipilih.',
        'guarantee_other': 'Keterangan jaminan wajib diisi.',
        'alamat': 'Alamat rumah wajib diisi.'
    };

    return messages[field.name] || 'Field ini wajib diisi.';
}

// Confirm Modal
function fillConfirmModal() {
    const unitSelect = document.getElementById('unitSelect');
    const paketSelect = document.getElementById('paketSelect');
    const paymentSelect = document.getElementById('paymentSelect');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    const unitSelected = unitSelect.options[unitSelect.selectedIndex];
    const unitText = unitSelected.text;

    // Durasi text
    let durasiText = '';
    if (paketSelect.value === 'lainnya') {
        durasiText = durasiCustomInput.value + ' Hari (Custom)';
    } else {
        const paketSelected = paketSelect.options[paketSelect.selectedIndex];
        durasiText = paketSelected.text;
    }

    const paymentText = (paymentSelect && paymentSelect.tagName === 'SELECT')
    ? paymentSelect.options[paymentSelect.selectedIndex].text
    : 'Tunai';

    const startDateObj = new Date(startDate.value);
    const endDateObj = new Date(endDate.value);

    const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };

    const startFormatted = startDateObj.toLocaleDateString('id-ID', options);
    const endFormatted = endDateObj.toLocaleDateString('id-ID', options);

    document.getElementById('confirmUnit').innerText = unitText;
    document.getElementById('confirmDurasi').innerText = durasiText;
    document.getElementById('confirmPayment').innerText = paymentText;
    document.getElementById('confirmStart').innerText = startFormatted;
    document.getElementById('confirmEnd').innerText = endFormatted;
    document.getElementById('confirmHargaSewa').innerText = sewaDisplay.innerText;
    document.getElementById('confirmHargaJaminan').innerText = jaminanDisplay.innerText;
    document.getElementById('confirmHarga').innerText = hargaDisplay.innerText;
}

/* =========================
   CONFIRM MODAL
========================= */

const cancelModalBtn = document.getElementById('cancelModal');
const tambahDurasiModal = document.getElementById('tambahDurasiModal');
const tdHariInput = document.getElementById('tdHariInput');
let currentSewaId = null;
let currentJenisUnitId = null;
const harga1HariMap = window.harga1HariMap || {};

function getHarga1Hari(jenisUnitId) {
    if (!jenisUnitId) return 0;
    if (harga1HariMap[jenisUnitId] !== undefined) {
        return parseInt(harga1HariMap[jenisUnitId]) || 0;
    }

    let harga = 0;
    if (paketSelect) {
        Array.from(paketSelect.options).forEach(option => {
            if (option.getAttribute('data-jenis') === jenisUnitId && parseInt(option.getAttribute('data-hari')) === 1) {
                harga = parseInt(option.getAttribute('data-harga')) || harga;
            }
        });
    }
    return harga;
}

function setSubmitting(flag) {
    isSubmitting = !!flag;
}

const confirmModal = document.getElementById('confirmModal');
if (cancelModalBtn) {
    cancelModalBtn.addEventListener('click', function () {
        closeModalElement(confirmModal);
    });
}

if (confirmModal) {
    confirmModal.addEventListener('click', function (e) {
        if (e.target === confirmModal) {
            closeModalElement(confirmModal);
        }
    });
}

function closeModalElement(el) {
    if (!el) return;
    el.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function handleSnapClose(message, shouldReload = false) {
    Swal.fire({
        icon: 'info',
        title: 'Pembayaran Dibatalkan',
        text: message || 'Transaksi belum selesai. Silakan coba lagi.',
        confirmButtonColor: '#6366f1'
    }).then(() => {
        if (shouldReload) {
            window.location.reload();
        }
    });
}

function handleSnapError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Pembayaran Gagal',
        text: message || 'Terjadi kesalahan. Silakan coba lagi.',
        confirmButtonColor: '#6366f1'
    });
}

const confirmSubmitBtn = document.getElementById('confirmSubmit');
if (confirmSubmitBtn) {
    confirmSubmitBtn.addEventListener('click', function () {
        if (isSubmitting) return;
        setSubmitting(true);

        closeModalElement(document.getElementById('confirmModal'));

        const formData = new FormData(rentalForm);

        fetch(window.sewaStoreUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.snap_token) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sewa Diajukan',
                    text: data.message || 'Sewa berhasil dibuat dan menunggu konfirmasi staf.',
                    confirmButtonColor: '#6366f1'
                }).then(() => window.location.reload());
                return;
            }

            window.snap.pay(data.snap_token, {
                onSuccess: function() {
                    fetch(`/sewa/${data.sewa_id}/konfirmasi-midtrans`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil',
                            text: 'Sewa berhasil dikonfirmasi.',
                            confirmButtonColor: '#6366f1'
                        }).then(() => window.location.reload());
                    });
                },
                onPending: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran belum selesai. Silakan selesaikan pembayaran.',
                        confirmButtonColor: '#6366f1'
                    }).then(() => window.location.reload());
                },
                onError: function() {
                    handleSnapError('Terjadi kesalahan saat pembayaran. Data sewa tetap tersimpan.');
                },
                onClose: function() {
                    handleSnapClose('Kamu menutup pembayaran. Data sewa tetap tersimpan.', true);
                }
            });
        })
        .catch(err => {
            console.error(err);
            handleSnapError(err.message || 'Server error.');
        })
        .finally(() => setSubmitting(false));
    });
}

const addDurationButtons = document.querySelectorAll('.btn-tambah-durasi-customer');
if (addDurationButtons.length > 0) {
    addDurationButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            currentSewaId = this.dataset.sewaId;
            currentJenisUnitId = this.dataset.jenisUnitId;
            document.getElementById('tdKode').textContent = this.dataset.kode || '-';
            if (tdHariInput) tdHariInput.value = '';
            document.getElementById('tdHariPreview').textContent = '-';
            document.getElementById('tdHargaPreview').textContent = 'Rp ...';
            if (tambahDurasiModal) {
                tambahDurasiModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });
}

const closeTambahDurasiModalBtn = document.getElementById('closeTambahDurasiModal');
if (closeTambahDurasiModalBtn) {
    closeTambahDurasiModalBtn.addEventListener('click', function () {
        closeModalElement(tambahDurasiModal);
    });
}

if (tambahDurasiModal) {
    tambahDurasiModal.addEventListener('click', function (e) {
        if (e.target === tambahDurasiModal) {
            closeModalElement(tambahDurasiModal);
        }
    });
}

if (tdHariInput) {
    tdHariInput.addEventListener('input', function () {
        const hari = parseInt(this.value) || 0;
        const harga1Hari = getHarga1Hari(currentJenisUnitId);
        const total = harga1Hari * hari;
        document.getElementById('tdHariPreview').textContent = hari ? `${hari} Hari` : '-';
        document.getElementById('tdHargaPreview').textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
}

const btnProsesTambahDurasi = document.getElementById('btnProsesTambahDurasi');
if (btnProsesTambahDurasi) {
    btnProsesTambahDurasi.addEventListener('click', function () {
        if (isSubmitting) return;
        const hari = parseInt(tdHariInput.value);
        if (!hari || hari < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Masukkan jumlah hari valid.',
                confirmButtonColor: '#6366f1'
            });
            return;
        }

        setSubmitting(true);
        fetch(`/sewa/${currentSewaId}/tambah-durasi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ tambah_hari: hari })
        })
        .then(response => response.json())
        .then(data => {
            closeModalElement(tambahDurasiModal);
            if (!data.snap_token) {
                Swal.fire({
                    icon: 'success',
                    title: 'Permintaan Diterima',
                    text: data.message || 'Tambah durasi berhasil dibuat.',
                    confirmButtonColor: '#6366f1'
                }).then(() => window.location.reload());
                return;
            }

            window.snap.pay(data.snap_token, {
                onSuccess: function() {
                    fetch(`/sewa/tambah-durasi/${data.tambah_durasi_id}/konfirmasi`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil',
                            text: 'Durasi sewa berhasil ditambah.',
                            confirmButtonColor: '#6366f1'
                        }).then(() => window.location.reload());
                    });
                },
                onPending: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran belum selesai. Silakan selesaikan pembayaran.',
                        confirmButtonColor: '#6366f1'
                    }).then(() => window.location.reload());
                },
                onError: function() {
                    handleSnapError('Terjadi kesalahan saat pembayaran tambah durasi. Data tetap tersimpan.');
                },
                onClose: function() {
                    handleSnapClose('Kamu menutup pembayaran tambah durasi. Data tetap tersimpan.', true);
                }
            });
        })
        .catch(err => {
            console.error(err);
            handleSnapError(err.message || 'Server error saat tambah durasi.');
        })
        .finally(() => setSubmitting(false));
    });
}

const pendingDurasiButtons = document.querySelectorAll('.btn-bayar-tambah-durasi');
if (pendingDurasiButtons.length > 0) {
    pendingDurasiButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const tambahDurasiId = this.dataset.tambahDurasiId;
            const snapToken = this.dataset.snapToken;
            if (!snapToken) return;

            window.snap.pay(snapToken, {
                onSuccess: function() {
                    fetch(`/sewa/tambah-durasi/${tambahDurasiId}/konfirmasi`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil',
                            confirmButtonColor: '#6366f1'
                        }).then(() => window.location.reload());
                    });
                },
                onPending: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran masih pending.',
                        confirmButtonColor: '#6366f1'
                    }).then(() => window.location.reload());
                },
                onError: function() {
                    handleSnapError('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    handleSnapClose('Kamu menutup pembayaran. Silakan coba lagi.', true);
                }
            });
        });
    });
}

const payNowButtons = document.querySelectorAll('.btn-bayar-sekarang');
if (payNowButtons.length > 0) {
    payNowButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const sewaId = this.dataset.sewaId;
            const snapToken = this.dataset.snapToken;
            if (!snapToken) return;

            window.snap.pay(snapToken, {
                onSuccess: function() {
                    fetch(`/sewa/${sewaId}/konfirmasi-midtrans`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil',
                            confirmButtonColor: '#6366f1'
                        }).then(() => window.location.reload());
                    });
                },
                onPending: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran masih pending.',
                        confirmButtonColor: '#6366f1'
                    }).then(() => window.location.reload());
                },
                onError: function() {
                    handleSnapError('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    handleSnapClose('Kamu menutup pembayaran. Silakan coba lagi.', true);
                }
            });
        });
    });
}

/* =========================
   MODAL DETAIL TRANSAKSI
========================= */

document.addEventListener('DOMContentLoaded', function () {
    // Select element
    const detailModal = document.getElementById('detailModal');
    const closeBtn = document.getElementById('closeDetailModal');
    const closeBtnText = document.getElementById('closeDetailModalBtn');
    const transactionLinks = document.querySelectorAll('.transaction-link');

    // Fungsi untuk membuka modal
    function openModal() {
        detailModal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Mencegah scroll di background
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        detailModal.classList.remove('active');
        document.body.style.overflow = 'auto'; // Kembalikan scroll
    }

    // Event: Klik link/transaksi di tabel
    if (transactionLinks.length > 0) {
        transactionLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                // Isi data ke dalam modal
                document.getElementById('detailCode').textContent = this.dataset.code || '-';
                document.getElementById('detailNama').textContent = this.dataset.nama || '-';
                document.getElementById('detailUnit').textContent = this.dataset.unit || '-';
                document.getElementById('detailDurasi').textContent = this.dataset.durasi || '-';
                document.getElementById('detailHp').textContent = this.getAttribute('data-no-hp') || '-';
                document.getElementById('detailMulai').textContent = this.dataset.mulai || '-';
                document.getElementById('detailSelesai').textContent = this.dataset.selesai || '-';
                document.getElementById('detailPembayaran').textContent = this.dataset.pembayaran || '-';
                document.getElementById('detailHargaSewa').textContent = this.dataset.hargaSewa || '-';
                document.getElementById('detailHargaJaminan').textContent = this.dataset.hargaJaminan || '-';
                document.getElementById('detailTotal').textContent = this.dataset.total || '-';
                document.getElementById('detailAlamat').textContent = this.dataset.alamat || '-';
                document.getElementById('detailJaminanType').textContent = this.dataset.jaminanType || '-';
                document.getElementById('detailJaminanOther').textContent = this.dataset.jaminanOther || '-';

                // Format Payment Status
                const paymentStatus = this.dataset.paymentStatus;
                document.getElementById('detailPaymentStatus').textContent =
                    paymentStatus === 'paid' ? 'Lunas' : 'Belum Lunas';

                // Format Status Sewa
                const statusMap = {
                    'pending': 'Pending',
                    'disewa': 'Disewa',
                    'extended': 'Extended',
                    'completed': 'Selesai',
                    'cancelled': 'Dibatalkan'
                };
                const statusVal = this.dataset.status;
                document.getElementById('detailStatus').textContent =
                    statusMap[statusVal] || statusVal;

                // Tampilkan modal
                openModal();
            });
        });
    }

    // Event: Klik tombol close (X)
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Event: Klik tombol "Tutup"
    if (closeBtnText) {
        closeBtnText.addEventListener('click', closeModal);
    }

    // Event: Klik di luar modal (background)
    if (detailModal) {
        detailModal.addEventListener('click', function (e) {
            if (e.target === detailModal) {
                closeModal();
            }
        });
    }

    // Event: Tekan ESC untuk menutup semua modal aktif
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (detailModal && detailModal.classList.contains('active')) {
                closeModal();
            }
            const confirmModal = document.getElementById('confirmModal');
            const tambahDurasiModal = document.getElementById('tambahDurasiModal');
            if (confirmModal && confirmModal.classList.contains('active')) {
                closeModalElement(confirmModal);
            }
            if (tambahDurasiModal && tambahDurasiModal.classList.contains('active')) {
                closeModalElement(tambahDurasiModal);
            }
        }
    });
});