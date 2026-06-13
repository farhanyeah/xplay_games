document.addEventListener('DOMContentLoaded', function () {

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
        if (input) input.style.borderColor = '#f87171';
    }

    function clearErrors(fields) {
        fields.forEach(function (fieldId) {
            const el = document.getElementById(fieldId + '-error');
            const input = document.getElementById(fieldId);
            if (el) {
                el.textContent = '';
                el.classList.add('d-none');
            }
            if (input) input.style.borderColor = '';
        });
    }

    // =========================================
    // HITUNG TOTAL
    // =========================================

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(function (row) {
            const select = row.querySelector('.select-item');
            const jumlah = row.querySelector('.input-jumlah');
            if (select && jumlah && select.value && jumlah.value) {
                const harga = parseInt(select.selectedOptions[0].dataset.harga) || 0;
                total += harga * parseInt(jumlah.value);
            }
        });
        document.getElementById('totalHarga').textContent =
            'Rp ' + total.toLocaleString('id-ID');
    }

    // =========================================
    // TAMBAH ITEM ROW
    // =========================================

    let itemIndex = 1;
    const stokOptions = document.querySelector('.select-item').innerHTML;

    document.getElementById('btnTambahItem').addEventListener('click', function () {
        const itemList = document.getElementById('itemList');
        const div = document.createElement('div');
        div.className = 'item-row d-flex mb-2';
        div.style.gap = '8px';
        div.innerHTML = `
            <select name="items[${itemIndex}][stok_id]" class="form-control ops-input select-item" style="flex:2;">
                ${stokOptions}
            </select>
            <input type="number" name="items[${itemIndex}][jumlah]" class="form-control ops-input input-jumlah" placeholder="Qty" min="1" style="flex:1;">
            <button type="button" class="ops-btn-aksi btn-hapus btn-remove-item" style="flex-shrink:0;">
                <i class="fas fa-times"></i>
            </button>
        `;
        itemList.appendChild(div);
        itemIndex++;

        div.querySelector('.select-item').addEventListener('change', hitungTotal);
        div.querySelector('.input-jumlah').addEventListener('input', hitungTotal);
        div.querySelector('.btn-remove-item').addEventListener('click', function () {
            div.remove();
            hitungTotal();
        });
    });

    // Event listener row pertama
    document.querySelectorAll('.select-item').forEach(function (el) {
        el.addEventListener('change', hitungTotal);
    });
    document.querySelectorAll('.input-jumlah').forEach(function (el) {
        el.addEventListener('input', hitungTotal);
    });
    document.querySelectorAll('.btn-remove-item').forEach(function (el) {
        el.addEventListener('click', function () {
            el.closest('.item-row').remove();
            hitungTotal();
        });
    });

    // =========================================
    // VALIDASI & SUBMIT TRANSAKSI
    // =========================================

    const tambahTransaksiForm = document.getElementById('tambahTransaksiForm');
    if (tambahTransaksiForm) {
        tambahTransaksiForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearErrors(['itemList', 'metodePembayaran']);
            let hasError = false;

            // Validasi item
            const rows = document.querySelectorAll('.item-row');
            let itemValid = true;
            rows.forEach(function (row) {
                const select = row.querySelector('.select-item');
                const jumlah = row.querySelector('.input-jumlah');
                if (!select.value || !jumlah.value || jumlah.value < 1) {
                    itemValid = false;
                }
            });

            if (rows.length === 0 || !itemValid) {
                showError('itemList', 'Pilih minimal 1 item dengan jumlah yang valid.');
                hasError = true;
            }

            // Validasi metode
            const metode = document.getElementById('metodePembayaran').value;
            if (!metode) {
                showError('metodePembayaran', 'Metode pembayaran wajib dipilih.');
                hasError = true;
            }

            if (hasError) return;

            // Validasi stok
            let stockError = false;
            let stockMessage = '';
            rows.forEach(function (row) {
                const select = row.querySelector('.select-item');
                const jumlah = row.querySelector('.input-jumlah');
                if (!select || !select.value) return;
                const option = select.selectedOptions[0];
                const stokTersedia = parseInt(option.dataset.stok || 0);
                const qty = parseInt(jumlah.value || 0);
                if (qty > stokTersedia) {
                    stockError = true;
                    stockMessage = 'Stok ' + option.dataset.nama + ' tidak mencukupi. Sisa stok: ' + stokTersedia;
                }
            });

            if (stockError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: stockMessage,
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            // Cash — submit biasa
            if (metode === 'cash') {
                this.submit();
                return;
            }

            // Midtrans — AJAX
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            try {
                const formData = new FormData(this);
                const res = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const text = await res.text();
                let data;

                try {
                    data = JSON.parse(text);
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Response tidak valid.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Buat Transaksi';
                    return;
                }

                if (!data.snap_token) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Snap token tidak ditemukan.'
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Buat Transaksi';
                    return;
                }

                $('#tambahTransaksiModal').modal('hide');

                window.snap.pay(data.snap_token, {
                    onSuccess: function () {
                        fetch('/operational/penjualan/' + data.penjualan_id + '/konfirmasi-pembayaran', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'X-HTTP-Method-Override': 'PATCH'
                            }
                        }).then(function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                text: 'Transaksi telah dikonfirmasi.',
                                confirmButtonColor: '#6366f1',
                            }).then(function () {
                                window.location.reload();
                            });
                        });
                    },
                    onPending: function () {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Belum Selesai',
                            text: 'Pembayaran belum selesai. Silakan selesaikan pembayaran.',
                            confirmButtonColor: '#6366f1'
                        }).then(function () {
                            window.location.reload();
                        });
                    },
                    onClose: function () {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Belum Selesai',
                            text: 'Silakan selesaikan pembayaran agar transaksi dapat dikonfirmasi.',
                            confirmButtonColor: '#6366f1'
                        }).then(function () {
                            window.location.reload();
                        });
                    },
                    onError: function () {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Buat Transaksi';
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan saat memproses pembayaran.',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Gagal memproses transaksi.'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Buat Transaksi';
            }
        });

        // Reset modal saat ditutup
        $('#tambahTransaksiModal').on('hidden.bs.modal', function () {
            clearErrors(['itemList', 'metodePembayaran']);
            tambahTransaksiForm.reset();
            const itemList = document.getElementById('itemList');
            itemList.innerHTML = `
                <div class="item-row d-flex mb-2" style="gap:8px;">
                    <select name="items[0][stok_id]" class="form-control ops-input select-item" style="flex:2;">
                        ${stokOptions}
                    </select>
                    <input type="number" name="items[0][jumlah]" class="form-control ops-input input-jumlah" placeholder="Qty" min="1" style="flex:1;">
                    <button type="button" class="ops-btn-aksi btn-hapus btn-remove-item" style="flex-shrink:0;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            itemIndex = 1;
            hitungTotal();
            itemList.querySelector('.select-item').addEventListener('change', hitungTotal);
            itemList.querySelector('.input-jumlah').addEventListener('input', hitungTotal);
            itemList.querySelector('.btn-remove-item').addEventListener('click', function () {
                this.closest('.item-row').remove();
                hitungTotal();
            });
        });
    }

    // =========================================
    // MIDTRANS — Bayar dari Tabel
    // =========================================

    document.querySelectorAll('.btn-midtrans').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const token = this.dataset.token;
            const id = this.dataset.id;

            if (!token) {
                Swal.fire({
                    icon: 'error',
                    title: 'Token Tidak Tersedia',
                    text: 'Token Midtrans tidak ditemukan.',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            window.snap.pay(token, {
                onSuccess: function () {
                    fetch('/operational/penjualan/' + id + '/konfirmasi-pembayaran', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'X-HTTP-Method-Override': 'PATCH'
                        }
                    }).then(function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Transaksi telah dikonfirmasi.',
                            confirmButtonColor: '#6366f1',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function () {
                            window.location.reload();
                        });
                    });
                },
                onPending: function () {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Belum Selesai',
                        text: 'Silakan selesaikan pembayaran agar transaksi dapat dikonfirmasi.',
                        confirmButtonColor: '#6366f1'
                    }).then(function () {
                        window.location.reload();
                    });
                },
                onClose: function () {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Belum Selesai',
                        text: 'Silakan selesaikan pembayaran agar transaksi dapat dikonfirmasi.',
                        confirmButtonColor: '#6366f1'
                    }).then(function () {
                        window.location.reload();
                    });
                },
                onError: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                        confirmButtonColor: '#6366f1'
                    });
                }
            });
        });
    });

    // =========================================
    // DELETE
    // =========================================

    document.querySelectorAll('.btn-delete-penjualan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const kode = this.dataset.kode;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Transaksi?',
                text: 'Transaksi "' + kode + '" akan dihapus dan stok dikembalikan.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8'
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.getElementById('deletePenjualanForm');
                    form.action = '/operational/penjualan/' + id;
                    form.submit();
                }
            });
        });
    });

});