<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
    <p class="text-muted">Kelola identitas, aturan peminjaman, dan sistem aplikasi di sini.</p>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="list-group list-group-flush rounded" id="settings-tab" role="tablist">
                <a class="list-group-item list-group-item-action active fw-bold py-3" id="general-tab" data-bs-toggle="list" href="#general" role="tab">
                    <i class="bi bi-building me-2"></i> Pengaturan Umum
                </a>
                <a class="list-group-item list-group-item-action fw-bold py-3" id="borrowing-tab" data-bs-toggle="list" href="#borrowing" role="tab">
                    <i class="bi bi-clock-history me-2"></i> Aturan Peminjaman
                </a>
                <a class="list-group-item list-group-item-action fw-bold py-3" id="system-tab" data-bs-toggle="list" href="#system" role="tab">
                    <i class="bi bi-shield-lock me-2"></i> Keamanan & Sistem
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= URLROOT; ?>/settings/update" method="POST">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <!-- TAB 1: GENERAL -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Identitas Aplikasi & Laporan</h5>
                            <?php if(isset($data['settings']['general'])): ?>
                                <?php foreach($data['settings']['general'] as $setting): ?>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= $setting->description; ?></label>
                                        <input type="text" class="form-control" name="<?= $setting->setting_key; ?>" value="<?= htmlspecialchars($setting->setting_value); ?>" required>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                            </div>
                        </div>

                        <!-- TAB 2: BORROWING -->
                        <div class="tab-pane fade" id="borrowing" role="tabpanel">
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

                        <!-- TAB 3: SYSTEM -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Pemeliharaan Sistem</h5>
                            
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Fitur Sedang Dalam Pengembangan</strong><br>
                                    Fitur untuk Backup Database dan penggantian logo (Upload Image) akan tersedia pada update berikutnya.
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top">
                                <!-- Tombol belum berfungsi secara nyata, hanya ilustrasi -->
                                <button type="button" class="btn btn-success px-4 me-2" disabled><i class="bi bi-download me-1"></i> Backup Database (.sql)</button>
                                <button type="button" class="btn btn-outline-secondary px-4" disabled><i class="bi bi-image me-1"></i> Ganti Logo</button>
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
