<?php $this->view('layouts/header', $data); ?>

<?php $b = $data['borrowing']; ?>

<div class="page-header">
    <h4><?= $data['title']; ?></h4>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">

        <!-- Borrowing Info -->
        <div class="card detail-card mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="detail-label">Kode</div>
                        <div class="detail-value"><code><?= $b->code; ?></code></div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Peminjam</div>
                        <div class="detail-value fw-bold"><?= $b->user_name; ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Tgl Pinjam</div>
                        <div class="detail-value"><?= formatDate($b->borrow_date, 'short'); ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Tgl Jatuh Tempo</div>
                        <div class="detail-value">
                            <?= formatDate($b->due_date, 'short'); ?>
                            <?php if($data['late_days'] > 0): ?>
                                <span class="badge bg-danger ms-1">Terlambat <?= $data['late_days']; ?> hari</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Form -->
        <div class="card form-card">
            <div class="card-header">
                <h5><i class="bi bi-arrow-down-left-circle me-2"></i>Form Pengembalian</h5>
            </div>
            <div class="card-body">
                <form action="<?= URLROOT; ?>/return/store" method="POST">
                    <input type="hidden" name="borrowing_id" value="<?= $b->id; ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                            <input type="date" name="return_date" id="return_date" class="form-control" 
                                   value="<?= $data['return_date']; ?>">
                        </div>
                        <div class="col-md-8">
                            <label for="notes" class="form-label">Catatan</label>
                            <input type="text" name="notes" id="notes" class="form-control" 
                                   placeholder="Catatan pengembalian (opsional)">
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3"><i class="bi bi-clipboard-check me-1"></i> Pemeriksaan Kondisi Alat</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Alat</th>
                                    <th class="text-center" style="width:80px;">Jumlah</th>
                                    <th style="width:200px;">Kondisi</th>
                                    <th>Catatan Kerusakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['details'] as $index => $detail): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= $detail->tool_name; ?></div>
                                        <small class="text-muted"><?= $detail->tool_code; ?></small>
                                    </td>
                                    <td class="text-center"><?= $detail->quantity; ?></td>
                                    <td>
                                        <select name="condition[<?= $index; ?>]" class="form-select form-select-sm">
                                            <option value="BAIK">Baik</option>
                                            <option value="RUSAK_RINGAN">Rusak Ringan</option>
                                            <option value="RUSAK_BERAT">Rusak Berat</option>
                                            <option value="HILANG">Hilang</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="damage_note[<?= $index; ?>]" class="form-control form-control-sm" 
                                               placeholder="Jelaskan kerusakan jika ada">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($data['late_days'] > 0): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Perhatian:</strong> Pengembalian terlambat <strong><?= $data['late_days']; ?> hari</strong>. 
                        Denda keterlambatan akan dihitung otomatis (Rp 5.000/hari/item).
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Proses pengembalian ini?')">
                            <i class="bi bi-check-circle me-1"></i> Proses Pengembalian
                        </button>
                        <a href="<?= URLROOT; ?>/borrowing/show/<?= $b->id; ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php $this->view('layouts/footer'); ?>
