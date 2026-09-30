<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h5><i class="bi bi-pencil-square me-2"></i>Form Edit Alat</h5>
            </div>
            <div class="card-body">
                <form action="<?= URLROOT; ?>/tool/update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $data['id']; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" 
                                   class="form-control <?= !empty($data['code_err']) ? 'is-invalid' : ''; ?>" 
                                   value="<?= $data['code']; ?>">
                            <div class="invalid-feedback"><?= $data['code_err']; ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" 
                                   class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : ''; ?>" 
                                   value="<?= $data['name']; ?>">
                            <div class="invalid-feedback"><?= $data['name_err']; ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" 
                                    class="form-select <?= !empty($data['category_err']) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?= $cat->id; ?>" <?= $data['category_id'] == $cat->id ? 'selected' : ''; ?>>
                                        <?= $cat->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= $data['category_err']; ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" 
                                   class="form-control <?= !empty($data['quantity_err']) ? 'is-invalid' : ''; ?>" 
                                   value="<?= $data['quantity']; ?>" min="1">
                            <div class="invalid-feedback"><?= $data['quantity_err']; ?></div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">Gambar Alat <span class="text-muted small">(Opsional)</span></label>
                            <?php if(!empty($data['image'])): ?>
                                <div class="mb-2">
                                    <img src="<?= URLROOT; ?>/assets/img/tools/<?= $data['image']; ?>" alt="Preview" style="max-height: 100px; border-radius: 8px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" id="image" class="form-control <?= !empty($data['image_err']) ? 'is-invalid' : ''; ?>" accept="image/*">
                            <div class="invalid-feedback"><?= $data['image_err'] ?? ''; ?></div>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Max 2MB (JPG, PNG, WEBP).</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3"><?= $data['description']; ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="condition" class="form-label">Kondisi</label>
                            <select name="condition" id="condition" class="form-select">
                                <option value="BAIK" <?= $data['condition'] == 'BAIK' ? 'selected' : ''; ?>>Baik</option>
                                <option value="RUSAK_RINGAN" <?= $data['condition'] == 'RUSAK_RINGAN' ? 'selected' : ''; ?>>Rusak Ringan</option>
                                <option value="RUSAK_BERAT" <?= $data['condition'] == 'RUSAK_BERAT' ? 'selected' : ''; ?>>Rusak Berat</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" name="location" id="location" class="form-control" 
                                   value="<?= $data['location']; ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="TERSEDIA" <?= $data['status'] == 'TERSEDIA' ? 'selected' : ''; ?>>Tersedia</option>
                                <option value="DIPINJAM" <?= $data['status'] == 'DIPINJAM' ? 'selected' : ''; ?>>Dipinjam</option>
                                <option value="MAINTENANCE" <?= $data['status'] == 'MAINTENANCE' ? 'selected' : ''; ?>>Maintenance</option>
                                <option value="RUSAK" <?= $data['status'] == 'RUSAK' ? 'selected' : ''; ?>>Rusak</option>
                                <option value="TIDAK_AKTIF" <?= $data['status'] == 'TIDAK_AKTIF' ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Perbarui
                        </button>
                        <a href="<?= URLROOT; ?>/tool" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
