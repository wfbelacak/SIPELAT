<?php $this->view('layouts/header', $data); ?>

<?php $b = $data['borrowing']; ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="row">
    <!-- Main Info -->
    <div class="col-lg-8">
        <div class="card detail-card mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-text me-2"></i>Informasi Peminjaman</h5>
                <div><?= statusBadge($b->status); ?></div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="detail-label">Kode Peminjaman</div>
                        <div class="detail-value"><code><?= $b->code; ?></code></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Tanggal Pinjam</div>
                        <div class="detail-value"><?= formatDate($b->borrow_date, 'short'); ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Tanggal Kembali</div>
                        <div class="detail-value">
                            <?= formatDate($b->due_date, 'short'); ?>
                            <?php if($b->status == 'DIPINJAM' && strtotime($b->due_date) < time()): ?>
                                <?php $lateDays = floor((time() - strtotime($b->due_date)) / 86400); ?>
                                <span class="badge bg-danger ms-1">Terlambat <?= $lateDays; ?> hari</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="detail-label">Peminjam</div>
                        <div class="detail-value fw-bold"><?= $b->user_name; ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Email</div>
                        <div class="detail-value"><?= $b->email; ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Telepon</div>
                        <div class="detail-value"><?= $b->phone ?? '-'; ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="detail-label">Tujuan</div>
                        <div class="detail-value"><?= $b->purpose; ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Catatan</div>
                        <div class="detail-value"><?= $b->notes ?? '-'; ?></div>
                    </div>
                </div>
                <?php if($b->approved_by_name): ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="detail-label">Diproses Oleh</div>
                        <div class="detail-value"><?= $b->approved_by_name; ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Tanggal Diproses</div>
                        <div class="detail-value"><?= formatDate($b->approved_at, 'datetime'); ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Items -->
        <div class="card table-card mb-3">
            <div class="card-header">
                <h5><i class="bi bi-list-check me-2"></i>Daftar Alat yang Dipinjam</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Kode Alat</th>
                                <th>Nama Alat</th>
                                <th class="text-center">Jumlah</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($data['details'] as $detail): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><code><?= $detail->tool_code; ?></code></td>
                                <td><?= $detail->tool_name; ?></td>
                                <td class="text-center"><?= $detail->quantity; ?></td>
                                <td><?= $detail->notes ?? '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <div class="card detail-card mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-gear me-2"></i>Aksi</h5>
            </div>
            <div class="card-body d-grid gap-2">

                <?php if(hasRole([1, 2])): ?>
                    <?php if($b->status == 'MENUNGGU'): ?>
                        <form action="<?= URLROOT; ?>/borrowing/approve/<?= $b->id; ?>" method="POST">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui peminjaman ini?')">
                                <i class="bi bi-check-circle me-1"></i> Setujui
                            </button>
                        </form>
                        <form action="<?= URLROOT; ?>/borrowing/reject/<?= $b->id; ?>" method="POST">
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak peminjaman ini?')">
                                <i class="bi bi-x-circle me-1"></i> Tolak
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if($b->status == 'DISETUJUI'): ?>
                        <form action="<?= URLROOT; ?>/borrowing/handover/<?= $b->id; ?>" method="POST">
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Konfirmasi serah terima alat?')">
                                <i class="bi bi-hand-index me-1"></i> Serah Terima Alat
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if($b->status == 'DIPINJAM'): ?>
                        <a href="<?= URLROOT; ?>/return/create/<?= $b->id; ?>" class="btn btn-info w-100 text-white">
                            <i class="bi bi-arrow-down-left-circle me-1"></i> Proses Pengembalian
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(in_array($b->status, ['DRAFT', 'MENUNGGU'])): ?>
                    <?php if(hasRole([3]) && $b->user_id == $_SESSION['user_id'] || hasRole([1, 2])): ?>
                    <form action="<?= URLROOT; ?>/borrowing/cancel/<?= $b->id; ?>" method="POST">
                        <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Batalkan peminjaman ini?')">
                            <i class="bi bi-x-lg me-1"></i> Batalkan
                        </button>
                    </form>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="<?= URLROOT; ?>/borrowing" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card detail-card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Info Waktu</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="detail-label">Dibuat</div>
                    <div class="detail-value"><?= formatDate($b->created_at, 'full'); ?></div>
                </div>
                <div class="mb-3">
                    <div class="detail-label">Diperbarui</div>
                    <div class="detail-value"><?= formatDate($b->updated_at, 'full'); ?></div>
                </div>
                <?php if($b->approved_at): ?>
                <div>
                    <div class="detail-label">Diproses</div>
                    <div class="detail-value"><?= formatDate($b->approved_at, 'full'); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
