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
    // VALIDASI TAMBAH
    // =========================================

    const tambahStokForm = document.getElementById('tambahStokForm');
    if (tambahStokForm) {
        tambahStokForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const fields = ['tambahNama', 'tambahKategori', 'tambahHarga', 'tambahStok', 'tambahSatuan'];
            clearErrors(fields);
            let hasError = false;

            if (!document.getElementById('tambahNama').value.trim()) {
                showError('tambahNama', 'Nama item tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahKategori').value) {
                showError('tambahKategori', 'Kategori wajib dipilih.'); hasError = true;
            }
            if (document.getElementById('tambahHarga').value === '') {
                showError('tambahHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }
            if (document.getElementById('tambahStok').value === '') {
                showError('tambahStok', 'Stok awal tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('tambahSatuan').value) {
                showError('tambahSatuan', 'Satuan wajib dipilih.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#tambahStokModal').on('hidden.bs.modal', function () {
            clearErrors(['tambahNama', 'tambahKategori', 'tambahHarga', 'tambahStok', 'tambahSatuan']);
            tambahStokForm.reset();
        });
    }

    // =========================================
    // VALIDASI EDIT
    // =========================================

    const editStokForm = document.getElementById('editStokForm');
    if (editStokForm) {
        editStokForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const fields = ['editNama', 'editKategori', 'editHarga', 'editStok', 'editSatuan'];
            clearErrors(fields);
            let hasError = false;

            if (!document.getElementById('editNama').value.trim()) {
                showError('editNama', 'Nama item tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editKategori').value) {
                showError('editKategori', 'Kategori wajib dipilih.'); hasError = true;
            }
            if (document.getElementById('editHarga').value === '') {
                showError('editHarga', 'Harga tidak boleh kosong.'); hasError = true;
            }
            if (document.getElementById('editStok').value === '') {
                showError('editStok', 'Stok tidak boleh kosong.'); hasError = true;
            }
            if (!document.getElementById('editSatuan').value) {
                showError('editSatuan', 'Satuan wajib dipilih.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#editStokModal').on('hidden.bs.modal', function () {
            clearErrors(['editNama', 'editKategori', 'editHarga', 'editStok', 'editSatuan']);
        });
    }

    // =========================================
    // VALIDASI RESTOCK
    // =========================================

    const restockForm = document.getElementById('restockForm');
    if (restockForm) {
        restockForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(['restockJumlah']);
            let hasError = false;

            const jumlah = document.getElementById('restockJumlah').value;
            if (!jumlah || jumlah < 1) {
                showError('restockJumlah', 'Jumlah restock minimal 1.'); hasError = true;
            }

            if (!hasError) this.submit();
        });

        $('#restockModal').on('hidden.bs.modal', function () {
            clearErrors(['restockJumlah']);
            restockForm.reset();
        });
    }

    // =========================================
    // EDIT
    // =========================================

    document.querySelectorAll('.btn-edit-stok').forEach(function (btn) {
        btn.addEventListener('click', function () {


            document.getElementById('editNama').value     = this.dataset.nama;
            document.getElementById('editKategori').value = this.dataset.kategori;
            document.getElementById('editHarga').value    = this.dataset.harga;
            document.getElementById('editStok').value     = this.dataset.stok;
            document.getElementById('editSatuan').value   = this.dataset.satuan;

            editStokForm.action = '/operational/stok/' + this.dataset.id;
            $('#editStokModal').modal('show');
        });
    });

    // =========================================
    // RESTOCK
    // =========================================

    document.querySelectorAll('.btn-restock').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('restockNama').textContent = this.dataset.nama;
            restockForm.action = '/operational/stok/' + this.dataset.id + '/restock';
            $('#restockModal').modal('show');
        });
    });

    // =========================================
    // DELETE
    // =========================================

    document.querySelectorAll('.btn-delete-stok').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const nama = this.dataset.nama;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Item?',
                text: '"' + nama + '" akan dihapus permanen.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8'
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteStokForm');
                    form.action = '/operational/stok/' + id;
                    form.submit();
                }
            });
        });
    });

});