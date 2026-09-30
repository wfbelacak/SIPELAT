<?php $this->view('layouts/header', $data); ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><?= $data['title']; ?></h4>
    <a href="<?= URLROOT; ?>/category/create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['categories'])): ?>
                        <?php $no = 1; foreach($data['categories'] as $cat): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-semibold"><?= $cat->name; ?></td>
                            <td><?= $cat->description ?? '-'; ?></td>
                            <td><?= statusBadge($cat->status); ?></td>
                            <td>
                                <a href="<?= URLROOT; ?>/category/edit/<?= $cat->id; ?>" class="btn btn-sm btn-outline-warning btn-action" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= URLROOT; ?>/category/delete/<?= $cat->id; ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-folder"></i>
                                    <p>Belum ada data kategori</p>
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
