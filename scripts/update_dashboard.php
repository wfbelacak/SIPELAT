<?php
$file = 'app/views/dashboard/index.php';
$content = file_get_contents($file);

$start = strpos($content, '<?php else: ?>');
if ($start !== false) {
    $newDashboard = '<?php else: ?>
    <section class="hero-dashboard">
        <div class="hero-text">
            <h2>Halo, <?= htmlspecialchars($_SESSION[\'user_name\'] ?? \'Ahmad\'); ?> 👋</h2>
            <p>Selamat datang di sistem peminjaman alat.<br>Kelola peminjaman Anda dengan mudah dan cepat.</p>
        </div>
        <!-- Note: Need a hero image, using a placeholder or existing asset -->
        <!-- <img src="<?= URLROOT; ?>/assets/img/hero-illustration.png" alt="Hero" class="hero-image"> -->
    </section>

    <section class="stat-grid-peminjam">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon stat-blue"><i class="bi bi-clipboard2-check"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?= $data[\'total_peminjaman\']; ?></div>
                    <div class="stat-label">Total Peminjaman</div>
                </div>
            </div>
            <div class="stat-trend"><span class="trend-up"><i class="bi bi-arrow-up"></i></span> <span>1 minggu terakhir</span></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon stat-green"><i class="bi bi-arrow-up-right-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?= $data[\'aktif_dipinjam\']; ?></div>
                    <div class="stat-label">Peminjaman Aktif</div>
                </div>
            </div>
            <div class="stat-trend"><span class="trend-up"><i class="bi bi-arrow-up"></i></span> <span>1 minggu terakhir</span></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon stat-yellow"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?= $data[\'menunggu\']; ?></div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                </div>
            </div>
            <div class="stat-trend"><span class="trend-neutral"><i class="bi bi-dash"></i></span> <span>1 minggu terakhir</span></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon stat-red"><i class="bi bi-check2-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?= $data[\'selesai\']; ?></div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
            <div class="stat-trend"><span class="trend-up"><i class="bi bi-arrow-up"></i></span> <span>1 minggu terakhir</span></div>
        </div>
    </section>

    <div class="section-header">
        <h3>Alat Tersedia</h3>
        <a href="<?= URLROOT; ?>/tool">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    
    <section class="tool-grid">
    <?php if(!empty($data[\'available_tools\'])): foreach($data[\'available_tools\'] as $tool): ?>
        <a href="<?= URLROOT; ?>/tool/detail/<?= $tool->id; ?>" class="tool-card" style="text-decoration:none;">
            <div class="tool-img-wrap">
                <img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool); ?>" alt="<?= htmlspecialchars($tool->name); ?>">
            </div>
            <div class="tool-info">
                <h4 class="tool-title"><?= htmlspecialchars($tool->name); ?></h4>
                <div class="tool-stock">
                    <i class="bi bi-circle-fill" style="font-size: 8px;"></i> <?= (int)$tool->available_qty; ?> unit tersedia
                </div>
            </div>
        </a>
    <?php endforeach; else: ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #64748b;">Tidak ada alat yang tersedia saat ini.</div>
    <?php endif; ?>
    </section>
<?php endif; ?>';
    
    $content = substr($content, 0, $start) . $newDashboard;
    file_put_contents($file, $content);
    echo "Dashboard updated.";
} else {
    echo "Could not find start point.";
}
