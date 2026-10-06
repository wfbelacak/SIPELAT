<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
    <p class="text-muted">Kelola aturan peminjaman sistem aplikasi di sini.</p>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="list-group list-group-flush rounded" id="settings-tab" role="tablist">
                <a class="list-group-item list-group-item-action active fw-bold py-3" id="borrowing-tab" data-bs-toggle="list" href="#borrowing" role="tab">
                    <i class="bi bi-clock-history me-2"></i> Aturan Peminjaman
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= URLROOT; ?>/settings/update" method="POST">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <!-- TAB 2: BORROWING (Now the only tab) -->
                        <div class="tab-pane fade show active" id="borrowing" role="tabpanel">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Aturan Batas Waktu & Denda</h5>
                            <?php if(isset($data['settings']['borrowing'])): ?>
                                <?php foreach($data['settings']['borrowing'] as $setting): ?>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= $setting->description; ?></label>
                                        <?php if($setting->setting_key == 'fine_per_day'): ?>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" name="<?= $setting->setting_key; ?>" value="<?= htmlspecialchars($setting->setting_value); ?>" required>
                                            </div>
                                        <?php elseif($setting->setting_key == 'max_borrow_days'): ?>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="<?= $setting->setting_key; ?>" value="<?= htmlspecialchars($setting->setting_value); ?>" required>
                                                <span class="input-group-text">Hari</span>
                                            </div>
                                        <?php else: ?>
                                            <input type="text" class="form-control" name="<?= $setting->setting_key; ?>" value="<?= htmlspecialchars($setting->setting_value); ?>" required>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item.active {
    background-color: #f0f4f8 !important;
    color: #1264e8 !important;
    border-color: transparent;
    border-left: 4px solid #1264e8;
}
.list-group-item {
    border-left: 4px solid transparent;
}
</style>

<?php $this->view('layouts/footer'); ?>
