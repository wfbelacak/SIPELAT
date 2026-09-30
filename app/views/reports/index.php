<?php $this->view('layouts/header', $data); ?>

<div class="page-header d-flex justify-content-between align-items-center d-print-none">
    <h4><?= $data['title']; ?></h4>
    <button class="btn btn-primary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>

<!-- Form Filter -->
<div class="card form-card mb-4 d-print-none">
    <div class="card-body">
        <form action="<?= URLROOT; ?>/report" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $data['start_date']; ?>">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $data['end_date']; ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Print Header (Hanya muncul saat print) -->
<div class="d-none d-print-block mb-4 print-header">
    <div class="d-flex align-items-center mb-3">
        <!-- Logo Instansi / Kampus -->
        <div style="width: 80px; height: 80px; margin-right: 20px; display: flex; align-items: center; justify-content: center;">
            <img src="<?= URLROOT; ?>/assets/img/logo-blue.png" alt="Logo" style="max-width: 100%; max-height: 100%;">
        </div>
        <div class="flex-grow-1 text-center" style="margin-right: 100px;"> <!-- Margin right to balance the logo -->
            <h3 class="fw-bolder mb-1" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 18pt;"><?= SITENAME; ?></h3>
            <h5 class="fw-bold mb-1" style="font-size: 14pt;">DIVISI SARANA DAN PRASARANA</h5>
            <p class="mb-0" style="font-size: 11pt;">Jl. Pendidikan No. 123, Kota Cerdas, Provinsi Teknologi 12345</p>
            <p class="mb-0" style="font-size: 10pt;">Telp: (021) 12345678 | Email: sarpras@instansi.ac.id | Web: www.instansi.ac.id</p>
        </div>
    </div>
    
    <!-- Garis Kop Surat -->
    <div style="border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin-bottom: 20px;"></div>
    
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="text-decoration: underline; font-size: 14pt;">LAPORAN OPERASIONAL PEMINJAMAN ALAT</h4>
        <p class="mb-0" style="font-size: 11pt;">Periode: <?= formatDate($data['start_date'], 'long'); ?> - <?= formatDate($data['end_date'], 'long'); ?></p>
    </div>
</div>

<!-- Statistik Area -->
<div class="row g-3 mb-4 print-stack">
    <!-- Kolom Kiri: Peminjaman & Pengembalian -->
    <div class="col-lg-6 print-full-width">
        <div class="card detail-card h-100">
            <div class="card-header bg-white print-card-header">
                <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 d-print-none"></i>A. RINGKASAN TRANSAKSI</h5>
            </div>
            <div class="card-body">
                <div class="print-stats-grid">
                    <div class="print-stat-box">
                        <div class="print-label">Total Peminjaman</div>
                        <div class="print-value"><?= $data['borrow_stats']->total_borrowings ?? 0; ?> <span style="font-size: 11pt; font-weight: normal;">transaksi</span></div>
                    </div>
                    <div class="print-stat-box">
                        <div class="print-label">Total Pengembalian</div>
                        <div class="print-value"><?= $data['return_stats']->total_returns ?? 0; ?> <span style="font-size: 11pt; font-weight: normal;">transaksi</span></div>
                    </div>
                    <div class="print-stat-box">
                        <div class="print-label">Transaksi Selesai</div>
                        <div class="print-value"><?= $data['borrow_stats']->completed ?? 0; ?> <span style="font-size: 11pt; font-weight: normal;">transaksi</span></div>
                    </div>
                    <div class="print-stat-box">
                        <div class="print-label">Peminjaman Aktif (Belum Kembali)</div>
                        <div class="print-value"><?= $data['borrow_stats']->active ?? 0; ?> <span style="font-size: 11pt; font-weight: normal;">transaksi</span></div>
                    </div>
                </div>
                
                <div class="print-fines-box mt-3">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 60%; vertical-align: top;">
                                <div class="print-label">Total Pendapatan Denda Keterlambatan:</div>
                                <div style="font-size: 10pt; margin-top: 5px;">(Berdasarkan <?= $data['return_stats']->late_returns ?? 0; ?> transaksi pengembalian yang terlambat)</div>
                            </td>
                            <td style="width: 40%; vertical-align: middle; text-align: right;">
                                <div class="print-value" style="font-size: 16pt;"><?= formatRupiah($data['return_stats']->total_fines ?? 0); ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Alat Populer -->
    <div class="col-lg-6 print-full-width">
        <div class="card table-card h-100">
            <div class="card-header bg-white print-card-header mt-print-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill text-warning me-2 d-print-none"></i>B. RINCIAN ALAT PALING SERING DIPINJAM</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 print-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%; text-align: center;">NO</th>
                                <th style="width: 25%; text-align: center;">KODE ALAT</th>
                                <th style="width: 50%;">NAMA ALAT</th>
                                <th style="width: 20%; text-align: center;">TOTAL DIPINJAM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['popular_tools'])): ?>
                                <?php $no=1; foreach($data['popular_tools'] as $tool): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center"><code class="text-dark"><?= $tool->code; ?></code></td>
                                    <td><?= $tool->name; ?></td>
                                    <td class="text-center fw-bold"><?= $tool->total_borrowed; ?> unit</td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">Belum ada data peminjaman di periode ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Signature Block (Hanya muncul saat print) -->
<div class="d-none d-print-block print-signature" style="margin-top: 60px; page-break-inside: avoid;">
    <div style="display: flex; justify-content: space-between; padding: 0 40px;">
        <div style="text-align: center; width: 300px;">
            <p style="margin-bottom: 70px;">Mengetahui,<br>Kepala Divisi Sarana & Prasarana</p>
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0;">Prof. Dr. Ir. Budi Santoso, M.T.</p>
            <p style="margin-bottom: 0;">NIP. 19750812 200112 1 003</p>
        </div>
        <div style="text-align: center; width: 300px;">
            <p style="margin-bottom: 70px;">Kota Cerdas, <?= date('d F Y'); ?><br>Petugas Administrasi</p>
            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0; text-transform: uppercase;"><?= $_SESSION['user_name'] ?? 'Admin'; ?></p>
            <p style="margin-bottom: 0;">NIP/NIK. 19900215 201504 1 001</p>
        </div>
    </div>
</div>

<style>
@media print {
    @page { margin: 2cm 2cm; size: A4 portrait; }
    
    /* Reset and Layout */
    body { background-color: #fff !important; color: #000 !important; font-family: 'Times New Roman', Times, serif !important; font-size: 11pt; line-height: 1.5; }
    .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
    .sidebar, .top-header, .page-header, .form-card, .d-print-none { display: none !important; }
    
    /* Flatten Cards */
    .card { border: none !important; box-shadow: none !important; border-radius: 0 !important; margin-bottom: 25px !important; padding: 0 !important; page-break-inside: avoid; }
    .print-card-header { background: transparent !important; padding: 0 0 5px 0 !important; border-bottom: none !important; margin-bottom: 10px !important; }
    .print-card-header h5 { font-size: 12pt !important; font-weight: bold !important; color: #000 !important; margin: 0 !important; }
    .card-body { padding: 0 !important; }
    
    /* Typography Overrides for Print */
    .text-primary, .text-success, .text-info, .text-danger, .text-warning { color: #000 !important; }
    .text-muted { color: #000 !important; }
    .fw-bold, .fw-bolder { font-weight: bold !important; }
    
    /* Layout Adjustments */
    .print-stack { display: block !important; }
    .print-full-width { width: 100% !important; max-width: 100% !important; flex: none !important; margin-bottom: 30px !important; padding: 0 !important; }
    .mt-print-4 { margin-top: 1.5rem !important; }
    
    /* Custom Stats Grid for Print */
    .print-stats-grid { display: flex; flex-wrap: wrap; border-top: 1px solid #000; border-left: 1px solid #000; }
    .print-stat-box { width: 50%; padding: 10px 15px; border-right: 1px solid #000; border-bottom: 1px solid #000; box-sizing: border-box; }
    .print-fines-box { border: 1px solid #000; padding: 10px 15px; }
    .print-label { font-size: 10pt !important; font-weight: bold !important; text-transform: uppercase; margin-bottom: 5px; }
    .print-value { font-size: 16pt !important; font-weight: bold !important; }
    
    /* Table */
    .print-table { width: 100% !important; border-collapse: collapse !important; margin-bottom: 0 !important; border: 1px solid #000; }
    .print-table th, .print-table td { border: 1px solid #000 !important; padding: 8px 12px !important; font-size: 11pt !important; color: #000 !important; vertical-align: middle; }
    .print-table th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-weight: bold !important; font-size: 10pt !important; }
    .print-table code { font-family: 'Times New Roman', Times, serif; font-size: 11pt !important; background: transparent !important; padding: 0 !important; color: #000 !important; }
}
</style>

<?php $this->view('layouts/footer'); ?>
