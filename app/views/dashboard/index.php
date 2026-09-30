<?php $this->view('layouts/header', $data); ?>

<?php if(hasRole([1,2])): ?>
<div class="dashboard-page-title">
    <span class="page-eyebrow">Dashboard</span>
    <h3>Selamat datang kembali, <?= htmlspecialchars($_SESSION['user_name']); ?>.</h3>
    <p>Pantau aktivitas peminjaman alat dari satu tempat.</p>
</div>

<div class="row g-4 mb-5">
<?php
$stats = [
    ['Total Alat', $data['total_alat'], 'bi-box-seam', 'bg-primary-soft', '+5%', 'trend-up'],
    ['Alat Tersedia', $data['alat_tersedia'] ?? ($data['total_alat'] - $data['alat_dipinjam']), 'bi-check-circle', 'bg-success-soft', '+12%', 'trend-up'],
    ['Menunggu Persetujuan', $data['menunggu'], 'bi-clock-history', 'bg-warning-soft', '+0%', 'trend-neutral'],
    ['Overdue', $data['overdue'], 'bi-exclamation-octagon', 'bg-danger-soft', '-3%', 'trend-down']
];
foreach($stats as $s): ?>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-2"><?= $s[0]; ?></div>
                        <h2 class="stat-value mb-0"><?= $s[1]; ?></h2>
                    </div>
                    <div class="icon-box <?= $s[3]; ?>"><i class="bi <?= $s[2]; ?>"></i></div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <span class="trend-indicator <?= $s[5]; ?> <?= $s[3]; ?> me-2"><?= $s[4]; ?></span>
                    <span class="text-muted small">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header border-0 pt-4 pb-0 d-flex justify-content-between">
                <h5 class="fw-bold mb-0">Peminjaman 7 Hari Terakhir</h5>
                <span class="small text-muted"><i class="bi bi-circle-fill me-1" style="color:#f6c51b;font-size:8px"></i>Dipinjam &nbsp; <i class="bi bi-circle-fill me-1" style="color:#18a765;font-size:8px"></i>Dikembalikan</span>
            </div>
            <div class="card-body p-4">
                <div style="position:relative;height:280px">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0">Status Alat</h5>
            </div>
            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                <div style="width:200px;height:200px;position:relative">
                    <canvas id="donutChart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <h3 class="fw-bold text-navy mb-0"><?= $data['total_alat']; ?></h3>
                        <span class="text-muted" style="font-size:.7rem">TOTAL ALAT</span>
                    </div>
                </div>
                <div class="w-100 mt-4 px-3">
                <?php foreach([['#18a765','Tersedia','80'],['#f6c51b','Dipinjam','30'],['#f59e0b','Maintenance','5'],['#e94b4b','Rusak','5']] as $l): ?>
                    <div class="d-flex justify-content-between small mb-3">
                        <span class="text-muted fw-semibold"><i class="bi bi-circle-fill me-2" style="color:<?= $l[0] ?>"></i><?= $l[1] ?></span>
                        <span class="fw-bold text-navy"><?= $l[2] ?></span>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center px-4 py-4 border-bottom">
        <h5 class="fw-bold mb-0">Peminjaman Terbaru</h5>
        <a href="<?= URLROOT; ?>/borrowing" class="text-decoration-none fw-bold" style="color:#b88900">Lihat Semua &rarr;</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($data['recent_table'])): foreach($data['recent_table'] as $row):
                    $bClass='badge-selesai';
                    if($row->status=='MENUNGGU')$bClass='badge-menunggu';
                    if($row->status=='DISETUJUI')$bClass='badge-disetujui';
                    if($row->status=='DIPINJAM')$bClass='badge-dipinjam';
                    if($row->status=='DIKEMBALIKAN')$bClass='badge-dikembalikan';
                    if($row->status=='DITOLAK'||$row->status=='DIBATALKAN')$bClass='badge-ditolak'; ?>
                    <tr>
                        <td class="fw-bold text-muted"><?= $row->code; ?></td>
                        <td class="fw-semibold text-navy"><?= htmlspecialchars($row->user_name); ?></td>
                        <td>Alat (Kuantitas)</td>
                        <td class="text-muted"><?= formatDate($row->borrow_date,'short'); ?></td>
                        <td><span class="badge-custom <?= $bClass; ?>"><?= ucfirst(strtolower($row->status)); ?></span></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada peminjaman baru</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',()=> {
    const l = document.getElementById('lineChart');
    if(l) new Chart(l, {
        type: 'line',
        data: {
            labels: <?= $data['chart_dates']??'[]'; ?>,
            datasets: [
                {label:'Dipinjam',data:<?= $data['chart_borrow']??'[]'; ?>,borderColor:'#f6c51b',backgroundColor:'rgba(246,197,27,.10)',tension:.4,fill:true,pointRadius:4},
                {label:'Dikembalikan',data:<?= $data['chart_return']??'[]'; ?>,borderColor:'#18a765',backgroundColor:'rgba(24,167,101,.08)',tension:.4,fill:true,pointRadius:4}
            ]
        },
        options: {
            responsive:true,
            maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true,ticks:{stepSize:1}},x:{grid:{display:false}}}
        }
    });

    const d = document.getElementById('donutChart');
    if(d) new Chart(d, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia','Dipinjam','Maintenance','Rusak','Tidak Aktif'],
            datasets: [{
                data: <?= $data['donut_data']??'[]'; ?>,
                backgroundColor: ['#18a765','#f6c51b','#f59e0b','#e94b4b','#4d5968'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {legend:{display:false}}
        }
    });
});
</script>

<?php else: ?>
    <!-- ========================================== -->
    <!-- DASHBOARD PEMINJAM (NEW DESIGN) -->
    <!-- ========================================== -->
    
    <!-- HERO GREETING -->
    <div class="card border-0 rounded-4 overflow-hidden mb-4" style="background: linear-gradient(135deg, #e8f0fe 0%, #d2e3fc 100%); position: relative;">
        <div class="card-body p-4 p-md-5">
            <div style="z-index: 2; position: relative;">
                <h2 class="fw-bold mb-2" style="color: #1264E8; font-size: 28px;">Halo, <?= htmlspecialchars(explode(' ', trim($_SESSION['user_name']))[0] ?? 'User') ?> 👋</h2>
                <p class="mb-1 fw-semibold text-dark" style="font-size: 15px;">Selamat datang di sistem peminjaman alat.</p>
                <p class="text-muted mb-0" style="font-size: 14px;">Kelola peminjaman Anda dengan mudah dan cepat.</p>
            </div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-5">
        <!-- Total Peminjaman -->
        <div class="col-6 col-md-3">
            <div class="card border border-light shadow-sm rounded-4 h-100 p-3" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;">
                <div class="d-flex gap-3 align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #e8f0fe; color: #1264E8; font-size: 1.2rem; flex-shrink: 0;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" style="font-size: 22px; color: #1e293b;"><?= $data['total_peminjaman'] ?></h3>
                        <div class="text-muted" style="font-size: 11px; font-weight: 500;">Total Peminjaman</div>
                    </div>
                </div>
                <div style="font-size: 10px; color: #10b981; font-weight: 600; padding-left: 58px;">
                    <i class="bi bi-arrow-up-short"></i> 1 minggu terakhir
                </div>
            </div>
        </div>
        <!-- Peminjaman Aktif -->
        <div class="col-6 col-md-3">
            <div class="card border border-light shadow-sm rounded-4 h-100 p-3" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;">
                <div class="d-flex gap-3 align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #dcfce7; color: #10b981; font-size: 1.2rem; flex-shrink: 0;">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" style="font-size: 22px; color: #1e293b;"><?= $data['aktif'] ?></h3>
                        <div class="text-muted" style="font-size: 11px; font-weight: 500;">Peminjaman Aktif</div>
                    </div>
                </div>
                <div style="font-size: 10px; color: #10b981; font-weight: 600; padding-left: 58px;">
                    <i class="bi bi-arrow-up-short"></i> 1 minggu terakhir
                </div>
            </div>
        </div>
        <!-- Menunggu Persetujuan -->
        <div class="col-6 col-md-3">
            <div class="card border border-light shadow-sm rounded-4 h-100 p-3" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;">
                <div class="d-flex gap-3 align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #fef9c3; color: #f59e0b; font-size: 1.2rem; flex-shrink: 0;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" style="font-size: 22px; color: #1e293b;"><?= $data['menunggu'] ?></h3>
                        <div class="text-muted" style="font-size: 11px; font-weight: 500;">Menunggu Persetujuan</div>
                    </div>
                </div>
                <div style="font-size: 10px; color: #10b981; font-weight: 600; padding-left: 58px;">
                    <i class="bi bi-arrow-up-short"></i> 1 minggu terakhir
                </div>
            </div>
        </div>
        <!-- Selesai -->
        <div class="col-6 col-md-3">
            <div class="card border border-light shadow-sm rounded-4 h-100 p-3" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02) !important;">
                <div class="d-flex gap-3 align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #fee2e2; color: #ef4444; font-size: 1.2rem; flex-shrink: 0;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" style="font-size: 22px; color: #1e293b;"><?= $data['selesai'] ?></h3>
                        <div class="text-muted" style="font-size: 11px; font-weight: 500;">Selesai</div>
                    </div>
                </div>
                <div style="font-size: 10px; color: #10b981; font-weight: 600; padding-left: 58px;">
                    <i class="bi bi-arrow-up-short"></i> 1 minggu terakhir
                </div>
            </div>
        </div>
    </div>

    <!-- ALAT TERSEDIA -->
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h5 class="fw-bold mb-0" style="color: #334155;">Alat Tersedia</h5>
        <a href="<?= URLROOT; ?>/tool" class="text-decoration-none fw-medium" style="font-size: 13px; color: #1264E8;">Lihat Semua &rarr;</a>
    </div>

    <div class="row g-3">
        <?php if(!empty($data['available_tools'])): foreach($data['available_tools'] as $tool): ?>
        <div class="col-6 col-md-3">
            <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id ?>" class="text-decoration-none">
                <div class="card border border-light shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,.08)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 2px 10px rgba(0,0,0,0.02)'">
                    <div class="p-4 bg-white text-center border-bottom border-light" style="height: 160px; display: flex; align-items: center; justify-content: center;">
                        <img src="<?= URLROOT; ?>/assets/img/tools/<?= toolImage($tool) ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <div class="card-body pt-3 pb-3 px-3">
                        <div class="fw-bold text-dark text-truncate mb-2" style="font-size: 15px;"><?= htmlspecialchars($tool->name) ?></div>
                        <div class="d-flex align-items-center" style="font-size: 12px; color: #10b981; font-weight: 600;">
                            <i class="bi bi-circle-fill me-2" style="font-size: 7px;"></i> <?= (int)$tool->available_qty ?> unit tersedia
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; else: ?>
        <div class="col-12 text-center text-muted py-5">Belum ada alat tersedia.</div>
        <?php endif; ?>
    </div>

<?php endif; ?>

<?php $this->view('layouts/footer'); ?>
