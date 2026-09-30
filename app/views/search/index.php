<?php $this->view('layouts/header', $data); ?>

<div class="section-header">
    <h3>Hasil Pencarian Global</h3>
    <?php if($data['keyword'] !== ''): ?>
    <p class="text-muted mb-0">
        Menampilkan hasil untuk kata kunci "<strong><?= htmlspecialchars($data['keyword']) ?></strong>"
    </p>
    <?php endif; ?>
</div>

<?php if($data['keyword'] === ''): ?>
    <div class="alert alert-info border-0 shadow-sm" style="background:#eaf2fa; color:#2d5885;">
        Silakan masukkan kata kunci di kotak pencarian di atas.
    </div>
<?php else: ?>

    <?php 
    $totalFound = count($data['tools']) + count($data['users']) + count($data['borrowings']);
    if($totalFound === 0): 
    ?>
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
            <h5 class="mt-3 text-muted">Tidak ditemukan hasil untuk "<?= htmlspecialchars($data['keyword']) ?>"</h5>
            <p class="text-muted" style="font-size: 0.9rem;">Coba gunakan kata kunci lain.</p>
        </div>
    <?php else: ?>

        <!-- SECTION: ALAT -->
        <?php if(count($data['tools']) > 0): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Data Alat (<?= count($data['tools']) ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Alat</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['tools'] as $t): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($t) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($t->name) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($t->code) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($t->category_name ?? 'Umum') ?></td>
                                    <td>
                                        <?php if($t->status === 'TERSEDIA'): ?>
                                            <span class="badge bg-success text-white">Tersedia</span>
                                        <?php elseif($t->status === 'MAINTENANCE'): ?>
                                            <span class="badge bg-warning text-dark">Maintenance</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary text-white"><?= htmlspecialchars($t->status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= (int)$t->available_qty ?> unit</td>
                                    <td>
                                        <a href="<?= URLROOT; ?>/tool/show/<?= $t->id ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION: PENGGUNA -->
        <?php if(count($data['users']) > 0): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-success"></i>Data Pengguna (<?= count($data['users']) ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['users'] as $u): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($u->username) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($u->email) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= getRoleName($u->role_id) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= URLROOT; ?>/user/edit/<?= $u->id ?>" class="btn btn-sm btn-outline-success">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION: PEMINJAMAN -->
        <?php if(count($data['borrowings']) > 0): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2 text-warning"></i>Data Peminjaman (<?= count($data['borrowings']) ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Resi</th>
                                    <th>Peminjam</th>
                                    <th>Alat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['borrowings'] as $b): ?>
                                <tr>
                                    <td><span class="fw-bold text-navy"><?= htmlspecialchars($b->code) ?></span></td>
                                    <td><?= htmlspecialchars($b->username) ?></td>
                                    <td><?= htmlspecialchars($b->tool_name) ?> (<?= (int)$b->qty ?>x)</td>
                                    <td>
                                        <?php
                                        $sc = 'bg-secondary';
                                        if($b->status==='MENUNGGU') $sc='bg-warning text-dark';
                                        if($b->status==='DISETUJUI') $sc='bg-info text-dark';
                                        if($b->status==='DIPINJAM') $sc='bg-primary text-white';
                                        if($b->status==='DIKEMBALIKAN' || $b->status==='SELESAI') $sc='bg-success text-white';
                                        if($b->status==='DITOLAK' || $b->status==='DIBATALKAN') $sc='bg-danger text-white';
                                        ?>
                                        <span class="badge <?= $sc ?>"><?= $b->status ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= URLROOT; ?>/borrowing/show/<?= $b->id ?>" class="btn btn-sm btn-outline-warning">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
<?php endif; ?>

<?php $this->view('layouts/footer'); ?>
