<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-header">
                <h5><i class="bi bi-pencil-square me-2"></i>Form Edit Kategori</h5>
            </div>
            <div class="card-body">
                <form action="<?= URLROOT; ?>/category/update" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" 
                               class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : ''; ?>" 
                               value="<?= $data['name']; ?>">
                        <div class="invalid-feedback"><?= $data['name_err']; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3"><?= $data['description']; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="AKTIF" <?= $data['status'] == 'AKTIF' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="NONAKTIF" <?= $data['status'] == 'NONAKTIF' ? 'selected' : ''; ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Perbarui
                        </button>
                        <a href="<?= URLROOT; ?>/category" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
