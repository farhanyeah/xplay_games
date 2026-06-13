document.addEventListener('DOMContentLoaded', function () {

    // =========================================
    // HELPER — Show & Clear Error
    // =========================================

    function showError(fieldId, message) {
        const el = document.getElementById(fieldId + '-error');
        const input = document.getElementById(fieldId);
        if (el) { el.textContent = message; el.classList.remove('d-none'); }
        if (input) input.style.borderColor = '#f87171';
    }

    function clearErrors(fields) {
        fields.forEach(function (fieldId) {
            const el = document.getElementById(fieldId + '-error');
            const input = document.getElementById(fieldId);
            if (el) { el.textContent = ''; el.classList.add('d-none'); }
            if (input) input.style.borderColor = '';
        });
    }

    // =========================================
    // VALIDASI TAMBAH SEWA
    // =========================================

    const tambahSewaForm = document.getElementById('tambahSewaForm');
    if (tambahSewaForm) {
        tambahSewaForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['tambahSewaJenis', 'tambahSewaDurasi', 'tambahSewaHarga']);
            let hasError = false;

            if (!document.getElementById('tambahSewaJenis').value) {
                showError('tambahSewaJenis', 'Tipe PS wajib dipilih.'); hasError = true;
            }
            if (!document.getElementById('tambahSewaDurasi').value) {
                showError('tambahSewaDurasi', 'Durasi tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahSewaHarga').value) {
                showError('tambahSewaHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#tambahSewaModal').on('hidden.bs.modal', function () {
            clearErrors(['tambahSewaJenis', 'tambahSewaDurasi', 'tambahSewaHarga']);
            tambahSewaForm.reset();
        });
    }

    // =========================================
    // VALIDASI EDIT SEWA
    // =========================================

    const editSewaForm = document.getElementById('editSewaForm');
    if (editSewaForm) {
        editSewaForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['editSewaJenis', 'editSewaDurasi', 'editSewaHarga']);
            let hasError = false;

            if (!document.getElementById('editSewaJenis').value) {
                showError('editSewaJenis', 'Tipe PS wajib dipilih.'); hasError = true;
            }
            if (!document.getElementById('editSewaDurasi').value) {
                showError('editSewaDurasi', 'Durasi tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editSewaHarga').value) {
                showError('editSewaHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#editSewaModal').on('hidden.bs.modal', function () {
            clearErrors(['editSewaJenis', 'editSewaDurasi', 'editSewaHarga']);
        });
    }

    // =========================================
    // VALIDASI TAMBAH BOOKING
    // =========================================

    const tambahBookingForm = document.getElementById('tambahBookingForm');
    if (tambahBookingForm) {
        tambahBookingForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['tambahBookingJenis', 'tambahBookingJam', 'tambahBookingHarga']);
            let hasError = false;

            if (!document.getElementById('tambahBookingJenis').value) {
                showError('tambahBookingJenis', 'Tipe PS wajib dipilih.'); hasError = true;
            }
            if (!document.getElementById('tambahBookingJam').value) {
                showError('tambahBookingJam', 'Jumlah jam tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahBookingHarga').value) {
                showError('tambahBookingHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#tambahBookingModal').on('hidden.bs.modal', function () {
            clearErrors(['tambahBookingJenis', 'tambahBookingJam', 'tambahBookingHarga']);
            tambahBookingForm.reset();
        });
    }

    // =========================================
    // VALIDASI EDIT BOOKING
    // =========================================

    const editBookingForm = document.getElementById('editBookingForm');
    if (editBookingForm) {
        editBookingForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['editBookingJenis', 'editBookingJam', 'editBookingHarga']);
            let hasError = false;

            if (!document.getElementById('editBookingJenis').value) {
                showError('editBookingJenis', 'Tipe PS wajib dipilih.'); hasError = true;
            }
            if (!document.getElementById('editBookingJam').value) {
                showError('editBookingJam', 'Jumlah jam tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editBookingHarga').value) {
                showError('editBookingHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#editBookingModal').on('hidden.bs.modal', function () {
            clearErrors(['editBookingJenis', 'editBookingJam', 'editBookingHarga']);
        });
    }

    // =========================================
    // VALIDASI TAMBAH KHUSUS
    // =========================================

    const tambahKhususForm = document.getElementById('tambahKhususForm');
    if (tambahKhususForm) {
        tambahKhususForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['tambahKhususNama', 'tambahKhususJenis', 'tambahKhususJam', 'tambahKhususHarga', 'tambahKhususMulai', 'tambahKhususSelesai', 'tambahKhususHari']);
            let hasError = false;

            if (!document.getElementById('tambahKhususNama').value.trim()) {
                showError('tambahKhususNama', 'Nama paket tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahKhususJenis').value) {
                showError('tambahKhususJenis', 'Tipe PS wajib dipilih.'); hasError = true;
            }
            if (!document.getElementById('tambahKhususJam').value) {
                showError('tambahKhususJam', 'Jumlah jam tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahKhususHarga').value) {
                showError('tambahKhususHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahKhususMulai').value) {
                showError('tambahKhususMulai', 'Jam mulai tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahKhususSelesai').value) {
                showError('tambahKhususSelesai', 'Jam selesai tidak boleh kosong.'); hasError = true;
            }

            const hariChecked = document.querySelectorAll('#tambahKhususModal input[name="hari_berlaku[]"]:checked');
            if (hariChecked.length === 0) {
                showError('tambahKhususHari', 'Pilih minimal 1 hari berlaku.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#tambahKhususModal').on('hidden.bs.modal', function () {
            clearErrors(['tambahKhususNama', 'tambahKhususJenis', 'tambahKhususJam', 'tambahKhususHarga', 'tambahKhususMulai', 'tambahKhususSelesai', 'tambahKhususHari']);
            tambahKhususForm.reset();
        });
    }

    // =========================================
    // VALIDASI EDIT KHUSUS
    // =========================================

    const editKhususForm = document.getElementById('editKhususForm');
    if (editKhususForm) {
        editKhususForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['editKhususNama', 'editKhususJenis', 'editKhususJam', 'editKhususHarga', 'editKhususMulai', 'editKhususSelesai', 'editKhususHari']);
            let hasError = false;

            if (!document.getElementById('editKhususNama').value.trim()) {
                showError('editKhususNama', 'Nama paket tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editKhususJenis').value) {
                showError('editKhususJenis', 'Tipe PS wajib dipilih.'); hasError = true;
            }
            if (!document.getElementById('editKhususJam').value) {
                showError('editKhususJam', 'Jumlah jam tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editKhususHarga').value) {
                showError('editKhususHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editKhususMulai').value) {
                showError('editKhususMulai', 'Jam mulai tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editKhususSelesai').value) {
                showError('editKhususSelesai', 'Jam selesai tidak boleh kosong.'); hasError = true;
            }

            const hariChecked = document.querySelectorAll('#editKhususModal input[name="hari_berlaku[]"]:checked');
            if (hariChecked.length === 0) {
                showError('editKhususHari', 'Pilih minimal 1 hari berlaku.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#editKhususModal').on('hidden.bs.modal', function () {
            clearErrors(['editKhususNama', 'editKhususJenis', 'editKhususJam', 'editKhususHarga', 'editKhususMulai', 'editKhususSelesai', 'editKhususHari']);
        });
    }

    // =========================================
    // EDIT & DELETE SEWA
    // =========================================

    document.querySelectorAll('.btn-edit-sewa').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editSewaJenis').value  = this.dataset.jenis;
            document.getElementById('editSewaDurasi').value = this.dataset.durasi;
            document.getElementById('editSewaHarga').value  = this.dataset.harga;
            editSewaForm.action = '/operational/kelola-harga/sewa/' + this.dataset.id;
            $('#editSewaModal').modal('show');
        });
    });

    document.querySelectorAll('.btn-delete-sewa').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id; const nama = this.dataset.nama;
            Swal.fire({
                icon: 'warning', title: 'Hapus Paket Sewa?',
                text: 'Paket "' + nama + '" akan dihapus permanen.',
                showCancelButton: true, confirmButtonText: 'Hapus', cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8'
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteSewaForm');
                    form.action = '/operational/kelola-harga/sewa/' + id;
                    form.submit();
                }
            });
        });
    });

    // =========================================
    // EDIT & DELETE BOOKING
    // =========================================

    document.querySelectorAll('.btn-edit-booking').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editBookingJenis').value = this.dataset.jenis;
            document.getElementById('editBookingJam').value   = this.dataset.jam;
            document.getElementById('editBookingHarga').value = this.dataset.harga;
            editBookingForm.action = '/operational/kelola-harga/booking/' + this.dataset.id;
            $('#editBookingModal').modal('show');
        });
    });

    document.querySelectorAll('.btn-delete-booking').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id; const nama = this.dataset.nama;
            Swal.fire({
                icon: 'warning', title: 'Hapus Paket Booking?',
                text: 'Paket "' + nama + '" akan dihapus permanen.',
                showCancelButton: true, confirmButtonText: 'Hapus', cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8'
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteBookingForm');
                    form.action = '/operational/kelola-harga/booking/' + id;
                    form.submit();
                }
            });
        });
    });

    // =========================================
    // EDIT & DELETE KHUSUS
    // =========================================

    document.querySelectorAll('.btn-edit-khusus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editKhususNama').value    = this.dataset.nama;
            document.getElementById('editKhususJenis').value   = this.dataset.jenis;
            document.getElementById('editKhususJam').value     = this.dataset.jam;
            document.getElementById('editKhususHarga').value   = this.dataset.harga;
            document.getElementById('editKhususMulai').value   = this.dataset.mulai;
            document.getElementById('editKhususSelesai').value = this.dataset.selesai;

            document.querySelectorAll('.edit-hari-checkbox').forEach(function (cb) { cb.checked = false; });

             // Mapping angka ke nama hari
            const hariMap = {
            '1': 'senin', '2': 'selasa', '3': 'rabu',
            '4': 'kamis', '5': 'jumat', '6': 'sabtu', '7': 'minggu'
        };

            const hariRaw = this.dataset.hari;
            const hari = (hariRaw && hariRaw !== 'null') ? JSON.parse(hariRaw) : [];
            
            hari.forEach(function (h) {
                const hariKey = hariMap[String(h)] || h;
                const cb = document.getElementById('edit_' + hariKey);
                if (cb) cb.checked = true;
            });

            editKhususForm.action = '/operational/kelola-harga/khusus/' + this.dataset.id;
            $('#editKhususModal').modal('show');
        });
    });

    document.querySelectorAll('.btn-delete-khusus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id; const nama = this.dataset.nama;
            Swal.fire({
                icon: 'warning', title: 'Hapus Paket Khusus?',
                text: 'Paket "' + nama + '" akan dihapus permanen.',
                showCancelButton: true, confirmButtonText: 'Hapus', cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8'
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteKhususForm');
                    form.action = '/operational/kelola-harga/khusus/' + id;
                    form.submit();
                }
            });
        });
    });

});