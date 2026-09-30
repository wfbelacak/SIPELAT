<?php $this->view('layouts/header', $data); ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
    <p class="text-muted">Menampilkan 500 aktivitas sistem terakhir.</p>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 180px;">Waktu</th>
                        <th>Pengguna</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['logs'])): ?>
                        <?php foreach($data['logs'] as $log): ?>
                        <tr>
                            <td><?= formatDate($log->created_at, 'datetime'); ?></td>
                            <td class="fw-semibold"><?= $log->user_name ?? '<span class="text-muted">System/Guest</span>'; ?></td>
                            <td><span class="badge bg-secondary"><?= strtoupper($log->module); ?></span></td>
                            <td>
                                <?php
                                $actionClass = 'text-dark';
                                if ($log->action == 'CREATE' || $log->action == 'APPROVE') $actionClass = 'text-success fw-bold';
                                if ($log->action == 'UPDATE' || $log->action == 'HANDOVER') $actionClass = 'text-primary fw-bold';
                                if ($log->action == 'DELETE' || $log->action == 'REJECT' || $log->action == 'CANCEL') $actionClass = 'text-danger fw-bold';
                                if ($log->action == 'COMPLETE') $actionClass = 'text-info fw-bold';
                                ?>
                                <span class="<?= $actionClass; ?>"><?= $log->action; ?></span>
                            </td>
                            <td><?= $log->description; ?></td>
                            <td><small class="text-muted"><?= $log->ip_address ?? '-'; ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-clock-history"></i>
                                    <p>Belum ada log aktivitas</p>
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
