<div class="modal fade ops-modal" id="modalRefundBilling" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formRefundBilling" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-undo mr-2"></i> Refund Billing
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="refund_billing_id">

                    <div class="form-group">
                        <label class="ops-filter-label">Unit</label>
                        <input type="text" id="refund_unit_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Customer</label>
                        <input type="text" id="refund_customer_display" class="form-control ops-input" readonly>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Nominal Refund <span class="text-danger">*</span></label>
                        <input type="number" name="nominal_refund" id="refund_nominal" class="form-control ops-input" min="1" required>
                    </div>

                    <div class="form-group">
                        <label class="ops-filter-label">Alasan Refund <span class="text-danger">*</span></label>
                        <textarea name="alasan" id="refund_alasan" class="form-control ops-input" rows="3" required></textarea>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Refund hanya dicatat manual, tidak otomatis ke Midtrans.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn ops-btn-reset" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn ops-btn-filter">
                        <i class="fas fa-save mr-1"></i> Simpan Refund
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>