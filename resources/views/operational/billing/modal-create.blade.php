<div class="modal fade ops-modal" id="modalCreateBilling" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formCreateBilling" method="POST" action="{{ route('operational.billing.store') }}">
            @csrf

            <input type="hidden" name="billing_unit_id" id="create_billing_unit_id">
            <input type="hidden" name="paket_harga_id" id="create_paket_harga_id">
            <input type="hidden" name="paket_khusus_id" id="create_paket_khusus_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i> Buat Billing
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="ops-filter-label">Unit</label>
                        <input type="text" id="create_unit_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Nama Customer <span class="text-danger">*</span></label>
                        <input type="text" name="nama_customer" class="form-control ops-input"
                               placeholder="Masukkan nama customer..." required>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Pilih Paket <span class="text-danger">*</span></label>
                        <select id="create_paket_select" class="form-control ops-input" required>
                            <option value="">Pilih paket...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Jumlah Jam</label>
                        <input type="number" name="jumlah_jam" id="create_jumlah_jam"
                               class="form-control ops-input" readonly required>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Total Harga</label>
                        <input type="text" id="create_harga_display"
                               class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Metode Bayar <span class="text-danger">*</span></label>
                        <select name="metode_bayar" class="form-control ops-input" required>
                            <option value="cash">Cash</option>
                            <option value="midtrans">Midtrans</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Catatan</label>
                        <textarea name="catatan" class="form-control ops-input"
                                  rows="3" style="height:auto;"
                                  placeholder="Opsional..."></textarea>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Billing akan dibuat dalam status hold. Sesi belum berjalan sampai staf klik Start.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn ops-btn-reset" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn ops-btn-filter">
                        <i class="fas fa-save mr-1"></i> Simpan Billing
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>