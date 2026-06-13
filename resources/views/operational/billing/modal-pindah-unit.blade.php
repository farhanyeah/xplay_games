<div class="modal fade ops-modal" id="modalPindahUnit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formPindahUnit" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exchange-alt mr-2"></i> Pindah Unit
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="pindah_billing_id">

                    <div class="form-group">
                        <label class="ops-filter-label">Unit Asal</label>
                        <input type="text" id="pindah_unit_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Customer</label>
                        <input type="text" id="pindah_customer_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Unit Tujuan <span class="text-danger">*</span></label>
                        <select name="ke_unit_id" id="pindah_unit_select" class="form-control ops-input" required>
                            <option value="">Pilih unit tersedia...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Alasan Pindah</label>
                        <textarea name="alasan" class="form-control ops-input" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn ops-btn-reset" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn ops-btn-filter">
                        <i class="fas fa-save mr-1"></i> Simpan Pindah
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>