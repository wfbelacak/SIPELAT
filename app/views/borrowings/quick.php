<?php $this->view('layouts/header', $data); $tool = $data['tool']; ?>

<a class="back-link" href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>">
    <i class="bi bi-arrow-left"></i> Kembali ke Detail Alat
</a>

<div class="row justify-content-center mt-3">
    <div class="col-lg-7">

        <!-- Tool Summary Card -->
        <div class="card border border-light shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-0 d-flex align-items-stretch">
                <div class="bg-light d-flex align-items-center justify-content-center p-3" style="min-width: 110px;">
                    <img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>" style="width: 80px; height: 80px; object-fit: contain;">
                </div>
                <div class="p-4 flex-grow-1">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #1e293b;"><?= htmlspecialchars($tool->name); ?></h5>
                            <div class="text-muted small mb-2">Kode: <?= htmlspecialchars($tool->code); ?></div>
                        </div>
                        <span class="badge rounded-pill px-3 py-2" style="background: #dcfce7; color: #16a34a; font-size: 12px; font-weight: 600;">
                            <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Tersedia
                        </span>
                    </div>
                    <div class="d-flex gap-3" style="font-size: 13px; color: #64748b;">
                        <span><i class="bi bi-grid me-1"></i><?= htmlspecialchars($tool->category_name ?? 'Umum'); ?></span>
                        <span><i class="bi bi-box-seam me-1"></i><?= (int)$tool->available_qty; ?> unit tersedia</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border border-light shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom border-light px-4 py-3">
                <h5 class="fw-bold mb-0" style="color: #1e293b;">
                    <i class="bi bi-journal-plus me-2 text-primary"></i>Form Peminjaman
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= URLROOT; ?>/borrowing/quickStore" method="POST">
                    <input type="hidden" name="tool_id" value="<?= $tool->id; ?>">

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-1" style="font-size: 14px;">Jumlah <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn btn-outline-secondary rounded-3" onclick="changeQty(-1)" style="width: 36px; height: 36px; padding: 0; font-size: 18px; line-height: 1;">−</button>
                            <input type="number" name="qty" id="qtyInput" class="form-control text-center fw-bold rounded-3" value="<?= (int)$data['qty']; ?>" min="1" max="<?= (int)$tool->available_qty; ?>" style="width: 70px;">
                            <button type="button" class="btn btn-outline-secondary rounded-3" onclick="changeQty(1)" style="width: 36px; height: 36px; padding: 0; font-size: 18px; line-height: 1;">+</button>
                            <span class="text-muted small">dari <?= (int)$tool->available_qty; ?> tersedia</span>
                        </div>
                        <?php if($data['qty_err']): ?>
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?= $data['qty_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Tanggal -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-semibold mb-1" style="font-size: 14px;">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="borrow_date" class="form-control rounded-3" value="<?= htmlspecialchars($data['borrow_date']); ?>" min="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold mb-1" style="font-size: 14px;">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control rounded-3" value="<?= htmlspecialchars($data['due_date']); ?>" min="<?= date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        <?php if($data['date_err']): ?>
                            <div class="col-12">
                                <div class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i><?= $data['date_err']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tujuan -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-1" style="font-size: 14px;">Tujuan Peminjaman <span class="text-danger">*</span></label>
                        <input type="text" name="purpose" class="form-control rounded-3" placeholder="Contoh: Praktik fotografi studio" value="<?= htmlspecialchars($data['purpose']); ?>">
                        <?php if($data['purpose_err']): ?>
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?= $data['purpose_err']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-1" style="font-size: 14px;">Catatan Tambahan <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="notes" class="form-control rounded-3" rows="3" placeholder="Catatan untuk petugas..."><?= htmlspecialchars($data['notes']); ?></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary fw-semibold px-5 rounded-3">
                            <i class="bi bi-send me-2"></i>Ajukan Peminjaman
                        </button>
                        <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn btn-outline-secondary rounded-3 px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const max = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>

<?php $this->view('layouts/footer'); ?>
