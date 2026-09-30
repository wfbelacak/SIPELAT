<?php $this->view('layouts/header', $data); ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><?= $data['title']; ?></h4>
        <p class="text-muted">Kelola foto profil, nama, dan kata sandi akun Anda.</p>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<?php
$avatarUrl = !empty($data['user']->avatar) 
    ? URLROOT . '/assets/img/avatars/' . $data['user']->avatar 
    : 'https://ui-avatars.com/api/?name=' . urlencode($data['user']->name) . '&background=1264E8&color=fff';
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body py-5">
                <div class="position-relative d-inline-block mb-3">
                    <img src="<?= $avatarUrl ?>" alt="Avatar" class="rounded-circle shadow" width="150" height="150" style="object-fit: cover;">
                </div>
                <h5 class="fw-bold mb-1"><?= $data['user']->name; ?></h5>
                <p class="text-muted mb-0"><?= $data['user']->role_name; ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= URLROOT; ?>/profile/update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="current_avatar" value="<?= $data['user']->avatar ?? ''; ?>">
                    
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Dasar</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ubah Foto Profil</label>
                        <input class="form-control" type="file" name="avatar" accept="image/png, image/jpeg">
                        <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($data['user']->name); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($data['user']->email); ?>" disabled>
                        <small class="text-muted">Email digunakan untuk masuk ke sistem dan tidak dapat diubah.</small>
                    </div>

                    <h5 class="fw-bold mb-4 border-bottom pb-2 mt-5">Keamanan Akun</h5>
                    <div class="alert alert-warning">Biarkan kosong jika Anda tidak ingin mengubah kata sandi.</div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kata Sandi Baru</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi baru">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control" name="password_confirm" placeholder="Ulangi kata sandi baru">
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
