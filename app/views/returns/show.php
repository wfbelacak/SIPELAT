<?php $this->view('layouts/header', $data); ?>

<?php $r = $data['return']; ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="row">
    <!-- Main Info -->
    <div class="col-lg-8">
        <div class="card detail-card mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-down-left-circle me-2"></i>Informasi Pengembalian</h5>
                <div><?= statusBadge($r->status); ?></div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="detail-label">Kode Peminjaman</div>
                        <div class="detail-value"><code><?= $r->borrowing_code; ?></code></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Peminjam</div>
                        <div class="detail-value fw-bold"><?= $r->user_name; ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Diterima Oleh</div>
                        <div class="detail-value"><?= $r->received_by_name ?? '-'; ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="detail-label">Tgl Pinjam</div>
                        <div class="detail-value"><?= formatDate($r->borrow_date, 'short'); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Tgl Jatuh Tempo</div>
                        <div class="detail-value"><?= formatDate($r->due_date, 'short'); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Tgl Dikembalikan</div>
                        <div class="detail-value"><?= formatDate($r->return_date, 'short'); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Keterlambatan</div>
                        <div class="detail-value">
                            <?php if($r->late_days > 0): ?>
                                <span class="badge bg-danger"><?= $r->late_days; ?> hari</span>
                            <?php else: ?>
                                <span class="badge bg-success">Tepat waktu</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="detail-label">Total Denda</div>
                        <div class="detail-value">
                            <?php if($r->total_fine > 0): ?>
                                <span class="text-danger fw-bold fs-5"><?= formatRupiah($r->total_fine); ?></span>
                            <?php else: ?>
                                <span class="text-success fw-bold">Tidak ada denda</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="detail-label">Catatan</div>
                        <div class="detail-value"><?= $r->notes ?? '-'; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Items -->
        <div class="card table-card mb-3">
            <div class="card-header">
                <h5><i class="bi bi-clipboard-check me-2"></i>Detail Kondisi Alat</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Kode</th>
                                <th>Nama Alat</th>
                                <th class="text-center">Jumlah</th>
                                <th>Kondisi</th>
                                <th>Catatan Kerusakan</th>
                                <th class="text-end">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($data['details'] as $detail): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><code><?= $detail->tool_code; ?></code></td>
                                <td><?= $detail->tool_name; ?></td>
                                <td class="text-center"><?= $detail->quantity; ?></td>
                                <td><?= conditionBadge($detail->condition); ?></td>
                                <td><?= $detail->damage_note ?? '-'; ?></td>
                                <td class="text-end"><?= $detail->fine > 0 ? formatRupiah($detail->fine) : '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="6" class="text-end fw-bold">Total Denda:</td>
                                <td class="text-end fw-bold text-danger"><?= formatRupiah($r->total_fine); ?></td>
                            </tr>
                        </tfoot>
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
                <?php if(hasRole([1, 2]) && $r->status === 'DIPERIKSA'): ?>
                    <form action="<?= URLROOT; ?>/return/complete/<?= $r->id; ?>" method="POST">
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Selesaikan pengembalian ini?')">
                            <i class="bi bi-check-circle me-1"></i> Selesaikan Pengembalian
                        </button>
                    </form>
                <?php endif; ?>

                <a href="<?= URLROOT; ?>/return" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <a href="<?= URLROOT; ?>/borrowing/show/<?= $r->borrowing_id; ?>" class="btn btn-outline-info w-100">
                    <i class="bi bi-file-text me-1"></i> Lihat Peminjaman
                </a>
            </div>
        </div>

        <div class="card detail-card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Info Waktu</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="detail-label">Dibuat</div>
                    <div class="detail-value"><?= formatDate($r->created_at, 'full'); ?></div>
                </div>
                <div>
                    <div class="detail-label">Diperbarui</div>
                    <div class="detail-value"><?= formatDate($r->updated_at, 'full'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('layouts/footer'); ?>
