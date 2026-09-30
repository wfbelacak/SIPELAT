<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Kode Peminjaman</th>
                        <?php if(hasRole([1, 2])): ?>
                        <th>Peminjam</th>
                        <?php endif; ?>
                        <th>Tgl Kembali</th>
                        <th class="text-center">Terlambat</th>
                        <th class="text-end">Total Denda</th>
                        <th>Status</th>
                        <th>Diterima Oleh</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['returns'])): ?>
                        <?php $no = 1; foreach($data['returns'] as $ret): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><code><?= $ret->borrowing_code; ?></code></td>
                            <?php if(hasRole([1, 2])): ?>
                            <td><?= $ret->user_name; ?></td>
                            <?php endif; ?>
                            <td><?= formatDate($ret->return_date, 'short'); ?></td>
                            <td class="text-center">
                                <?php if($ret->late_days > 0): ?>
                                    <span class="badge bg-danger"><?= $ret->late_days; ?> hari</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Tepat waktu</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= $ret->total_fine > 0 ? formatRupiah($ret->total_fine) : '-'; ?></td>
                            <td><?= statusBadge($ret->status); ?></td>
                            <td><?= $ret->received_by_name ?? '-'; ?></td>
                            <td>
                                <a href="<?= URLROOT; ?>/return/show/<?= $ret->id; ?>" class="btn btn-sm btn-outline-info btn-action" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= hasRole([1,2]) ? 9 : 8; ?>">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada data pengembalian</p>
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
