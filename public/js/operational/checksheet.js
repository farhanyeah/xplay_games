// ========================================
// KELOLA CHECKSHEET (OWNER)
// ========================================

document.addEventListener('DOMContentLoaded', function () {

    // --------- EDIT MODAL ---------
    const editButtons = document.querySelectorAll('.btn-edit-checksheet');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editName').value = this.dataset.name;
            document.getElementById('editFrequency').value = this.dataset.frekuensi;
            document.getElementById('editShift').value = this.dataset.shift;

            const checkbox = document.getElementById('editIsActive');
            checkbox.checked = this.dataset.active === '1';

            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.action = '/operational/checksheet/item/' + this.dataset.id + '/update';
            }

            $('#editItemModal').modal('show');
        });
    });

    // --------- DELETE SWEETALERT ---------
    const deleteButtons = document.querySelectorAll('.btn-delete-checksheet');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Item?',
                text: '"' + this.dataset.name + '" akan dihapus permanen!',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    if (form) {
                        form.action = '/operational/checksheet/item/' + this.dataset.id + '/delete';
                        form.submit();
                    }
                }
            });
        });
    });

    // --------- SIMPAN CHECKSHEET ---------
const btnSimpan = document.querySelector('.btn-simpan-checksheet');
if (btnSimpan) {
    btnSimpan.addEventListener('click', function(e) {
        e.preventDefault();

        // CEK: checkbox yang BELUM disabled
        const uncheckedItems = document.querySelectorAll('input[name^="items["]:not(:disabled)');
        const checkedItems = document.querySelectorAll('input[name^="items["]:checked');

        // Kalau SEMUA item sudah terisi (ga ada yang bisa centang)
        if (uncheckedItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Checksheet Sudah Terisi',
                text: 'Semua item sudah dicentang. Tidak ada yang bisa ditambahkan.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6366f1'
            });
            return;
        }

        // Validasi: minimal 1 item harus dicentang
        if (checkedItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Ada Item Dicek',
                text: 'Pilih minimal 1 item untuk disimpan.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6366f1'
            });
            return;
        }

        // Konfirmasi simpan
        const form = this.closest('form');
        Swal.fire({
            icon: 'question',
            title: 'Simpan Checksheet?',
            text: `${checkedItems.length} item akan disimpan.`,
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#94a3b8'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
}
});