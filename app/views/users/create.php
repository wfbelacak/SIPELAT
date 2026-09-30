<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card form-card">
            <div class="card-header">
                <h5><i class="bi bi-person-plus me-2"></i>Form Tambah Pengguna</h5>
            </div>
            <div class="card-body">
                <form action="<?= URLROOT; ?>/user/store" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" 
                                   class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : ''; ?>" 
                                   value="<?= $data['name']; ?>" placeholder="Nama lengkap">
                            <div class="invalid-feedback"><?= $data['name_err']; ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" 
                                   class="form-control <?= !empty($data['username_err']) ? 'is-invalid' : ''; ?>" 
                                   value="<?= $data['username']; ?>" placeholder="Username">
                            <div class="invalid-feedback"><?= $data['username_err']; ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" 
                                   class="form-control <?= !empty($data['email_err']) ? 'is-invalid' : ''; ?>" 
                                   value="<?= $data['email']; ?>" placeholder="email@example.com">
                            <div class="invalid-feedback"><?= $data['email_err']; ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" 
                                   class="form-control <?= !empty($data['password_err']) ? 'is-invalid' : ''; ?>" 
                                   placeholder="Minimal 6 karakter">
                            <div class="invalid-feedback"><?= $data['password_err']; ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" 
                                    class="form-select <?= !empty($data['role_err']) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Pilih Role --</option>
                                <option value="1" <?= $data['role_id'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                <option value="2" <?= $data['role_id'] == 2 ? 'selected' : ''; ?>>Petugas</option>
                                <option value="3" <?= $data['role_id'] == 3 ? 'selected' : ''; ?>>Peminjam</option>
                            </select>
                            <div class="invalid-feedback"><?= $data['role_err']; ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control" 
                                   value="<?= $data['phone']; ?>" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Alamat lengkap"><?= $data['address']; ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="AKTIF" <?= $data['status'] == 'AKTIF' ? 'selected' : ''; ?>>Aktif</option>
                                <option value="NONAKTIF" <?= $data['status'] == 'NONAKTIF' ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan
                        </button>
                        <a href="<?= URLROOT; ?>/user" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
