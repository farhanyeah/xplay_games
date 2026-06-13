<div class="modal fade ops-modal" id="modalExtendBilling" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formExtendBilling" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus mr-2"></i> Extend Billing
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="extend_billing_id">

                    <div class="form-group">
                        <label class="ops-filter-label">Unit</label>
                        <input type="text" id="extend_unit_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Customer</label>
                        <input type="text" id="extend_customer_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Pilih Paket Tambahan</label>
                        <select name="paket_harga_id" id="extend_paket_select" class="form-control ops-input" required>
                            <option value="">Pilih paket...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Metode Bayar</label>
                        <select name="metode_bayar" class="form-control ops-input" required>
                            <option value="cash">Cash</option>
                            <option value="midtrans">Midtrans</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Estimasi Harga Tambahan</label>
                        <input type="text" id="extend_harga_display" class="form-control ops-input" readonly>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Extend hanya akan langsung menambah waktu jika pembayaran cash sudah lunas.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn ops-btn-reset" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn ops-btn-filter">
                        <i class="fas fa-save mr-1"></i> Simpan Extend
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>