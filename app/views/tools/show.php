<?php $this->view('layouts/header', $data); $tool = $data['tool']; ?>

<a class="back-link" href="<?= URLROOT; ?>/tool"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Alat</a>

<div class="detail-layout">
    <div class="detail-main">
        
        <div class="detail-image-gallery">
            <div class="detail-img-main position-relative">
                <img id="detailMainImage" src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>" alt="<?= htmlspecialchars($tool->name); ?>">
                <span class="badge bg-secondary position-absolute bottom-0 end-0 m-3 opacity-75">1/4</span>
            </div>
            <div class="detail-img-thumbs">
                <div class="thumb active"><img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>"></div>
                <div class="thumb"><img src="<?= URLROOT; ?>/assets/img/tools/laptop.svg" onerror="this.src='<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>'"></div>
                <div class="thumb"><img src="<?= URLROOT; ?>/assets/img/tools/projector.svg" onerror="this.src='<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>'"></div>
                <div class="thumb"><img src="<?= URLROOT; ?>/assets/img/tools/camera.svg" onerror="this.src='<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>'"></div>
            </div>
        </div>
        
        <div class="detail-content">
            <h1><?= htmlspecialchars($tool->name); ?></h1>
            <div class="code">Kode Alat : <?= htmlspecialchars($tool->code); ?></div>
            
            <div class="detail-badges">
                <?php if($tool->available_qty > 0 && $tool->status === 'TERSEDIA'): ?>
                    <span class="badge-status tersedia m-0"><i class="bi bi-check-circle-fill me-1"></i> Tersedia</span>
                <?php else: ?>
                    <span class="badge-status dipinjam m-0"><?= htmlspecialchars(ucwords(strtolower(str_replace('_',' ',$tool->status)))); ?></span>
                <?php endif; ?>
            </div>
            
            <hr class="text-muted opacity-25 my-4">
            
            <div class="detail-stats-grid">
                <div class="stat-item"><i class="bi bi-grid text-muted"></i> Kategori : <strong class="text-cat mb-0" style="font-size:14px; color:var(--text-main);">@<?= htmlspecialchars($tool->category_name ?? 'Umum'); ?></strong></div>
                <div class="stat-item"><i class="bi bi-box-seam text-muted"></i> Jumlah : <strong><?= (int)$tool->quantity; ?></strong></div>
                <div class="stat-item"><i class="bi bi-check2-circle text-success"></i> Tersedia : <strong><?= (int)$tool->available_qty; ?></strong></div>
                <div class="stat-item"><i class="bi bi-arrow-repeat text-warning"></i> Dipinjam : <strong><?= max(0,(int)$tool->quantity-(int)$tool->available_qty); ?></strong></div>
            </div>

            <div class="detail-stats-grid">
                <div class="stat-item"><i class="bi bi-shield-check text-muted"></i> Kondisi : <span class="badge-status tersedia ms-1"> <?= htmlspecialchars(ucwords(strtolower(str_replace('_',' ',$tool->condition)))); ?></span></div>
                <div class="stat-item"><i class="bi bi-geo-alt text-muted"></i> Lokasi : <strong><?= htmlspecialchars($tool->location ?? 'Ruang A'); ?></strong></div>
            </div>
            
            <hr class="text-muted opacity-25 my-4">
            
            <div class="desc-section">
                <h4>Deskripsi</h4>
                <p><?= htmlspecialchars($tool->description ?: 'Alat untuk kegiatan praktik dan kegiatan operasional perusahaan. Spesifikasi mengikuti unit yang tersedia.'); ?></p>
            </div>
        </div>

    </div>
    
    <div class="detail-sidebar">
        <div class="action-box">
            <h4>Aksi</h4>
            <?php if($tool->available_qty > 0 && $tool->status === 'TERSEDIA'): ?>
                <a href="<?= URLROOT; ?>/borrowing/quick/<?= $tool->id; ?>" class="btn-primary-solid mb-3">
                    <i class="bi bi-journal-plus me-1"></i> Ajukan Peminjaman
                </a>
            <?php endif; ?>
            <a href="<?= URLROOT; ?>/tool" class="btn-outline-primary">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.thumb').forEach(t => {
    t.addEventListener('click', () => {
        document.querySelectorAll('.thumb').forEach(x => x.classList.remove('active'));
        t.classList.add('active');
        const img = t.querySelector('img');
        document.getElementById('detailMainImage').src = img.src;
    });
});
</script>

<?php $this->view('layouts/footer'); ?>