document.addEventListener('DOMContentLoaded', function () {

    // =========================================
    // TOGGLE PASSWORD VISIBILITY
    // =========================================

    document.querySelectorAll('.btn-toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // =========================================
    // HELPER — Show & Clear Error
    // =========================================

    function showError(fieldId, message) {
        const el = document.getElementById(fieldId + '-error');
        const input = document.getElementById(fieldId);
        if (el) {
            el.textContent = message;
            el.classList.remove('d-none');
        }
        if (input) {
            input.style.borderColor = '#f87171';
        }
    }

    function clearErrors(fields) {
        fields.forEach(function (fieldId) {
            const el = document.getElementById(fieldId + '-error');
            const input = document.getElementById(fieldId);
            if (el) {
                el.textContent = '';
                el.classList.add('d-none');
            }
            if (input) {
                input.style.borderColor = '';
            }
        });
    }

    // =========================================
    // VALIDASI TAMBAH
    // =========================================

    const tambahForm = document.querySelector('#tambahStafModal form');

    tambahForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name     = document.getElementById('tambahName').value.trim();
        const email    = document.getElementById('tambahEmail').value.trim();
        const password = document.getElementById('tambahPassword').value;

        clearErrors(['tambahName', 'tambahEmail', 'tambahPassword']);

        let hasError = false;

        if (!name) {
            showError('tambahName', 'Nama tidak boleh kosong.');
            hasError = true;
        }

        if (!email) {
            showError('tambahEmail', 'Email tidak boleh kosong.');
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError('tambahEmail', 'Format email tidak valid.');
            hasError = true;
        }

        if (!password) {
            showError('tambahPassword', 'Password tidak boleh kosong.');
            hasError = true;
        } else if (password.length < 8) {
            showError('tambahPassword', 'Password minimal 8 karakter.');
            hasError = true;
        }

        if (!hasError) {
            this.submit();
        }
    });

    // Reset error saat modal tambah ditutup
    $('#tambahStafModal').on('hidden.bs.modal', function () {
        clearErrors(['tambahName', 'tambahEmail', 'tambahPassword']);
        tambahForm.reset();
    });

    // =========================================
    // VALIDASI EDIT
    // =========================================

    const editForm = document.getElementById('editForm');

    editForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name     = document.getElementById('editName').value.trim();
        const email    = document.getElementById('editEmail').value.trim();
        const password = document.getElementById('editPassword').value;

        clearErrors(['editName', 'editEmail', 'editPassword']);

        let hasError = false;

        if (!name) {
            showError('editName', 'Nama tidak boleh kosong.');
            hasError = true;
        }

        if (!email) {
            showError('editEmail', 'Email tidak boleh kosong.');
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError('editEmail', 'Format email tidak valid.');
            hasError = true;
        }

        if (password && password.length < 8) {
            showError('editPassword', 'Password minimal 8 karakter.');
            hasError = true;
        }

        if (!hasError) {
            this.submit();
        }
    });

    // Reset error saat modal edit ditutup
    $('#editStafModal').on('hidden.bs.modal', function () {
        clearErrors(['editName', 'editEmail', 'editPassword']);
    });

    // =========================================
    // EDIT — Isi data ke modal
    // =========================================

    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const name  = this.dataset.name;
            const email = this.dataset.email;

            document.getElementById('editName').value    = name;
            document.getElementById('editEmail').value   = email;
            document.getElementById('editPassword').value = '';

            editForm.action = '/operational/kelola-staf/' + id;

            $('#editStafModal').modal('show');
        });
    });

    // =========================================
    // DELETE
    // =========================================

    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const name = this.dataset.name;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Staf?',
                text: 'Akun staf "' + name + '" akan dihapus permanen.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8'
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = '/operational/kelola-staf/' + id;
                    form.submit();
                }
            });
        });
    });

});