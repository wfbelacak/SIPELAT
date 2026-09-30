<?php $this->view('layouts/header', $data); ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><?= $data['title']; ?></h4>
    <a href="<?= URLROOT; ?>/user/create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Pengguna
    </a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login Terakhir</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['users'])): ?>
                        <?php $no = 1; foreach($data['users'] as $user): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-semibold"><?= $user->name; ?></td>
                            <td><code><?= $user->username; ?></code></td>
                            <td><?= $user->email; ?></td>
                            <td>
                                <?php 
                                    $roleClass = 'badge-role-peminjam';
                                    if ($user->role_name == 'Admin') $roleClass = 'badge-role-admin';
                                    elseif ($user->role_name == 'Petugas') $roleClass = 'badge-role-petugas';
                                ?>
                                <span class="badge <?= $roleClass; ?>"><?= $user->role_name; ?></span>
                            </td>
                            <td><?= statusBadge($user->status); ?></td>
                            <td><?= $user->last_login ? formatDate($user->last_login, 'datetime') : '<span class="text-muted">Belum pernah</span>'; ?></td>
                            <td>
                                <a href="<?= URLROOT; ?>/user/edit/<?= $user->id; ?>" class="btn btn-sm btn-outline-warning btn-action" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <?php if($user->id != $_SESSION['user_id']): ?>
                                <form action="<?= URLROOT; ?>/user/delete/<?= $user->id; ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <p>Belum ada data pengguna</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
