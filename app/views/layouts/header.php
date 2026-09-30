<?php
/* header.php – Admin/Petugas (sidebar) + Peminjam (top-navbar) */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Dashboard'; ?> — <?= SITENAME; ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Local Bootstrap -->
    <link href="<?= URLROOT; ?>/assets/bootstrap-5.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/bootstrap-5.3.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= URLROOT; ?>/assets/css/style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/css/design.css?v=<?= time(); ?>" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php if(hasRole([1, 2])): // ============ ADMIN & PETUGAS ============ ?>

    <!-- Sidebar -->
    <aside class="sidebar d-none d-lg-flex" id="sidebar">
        <a href="<?= URLROOT; ?>" class="sidebar-logo">
            <img src="<?= URLROOT; ?>/assets/img/logo-white-lengkap.png" alt="SiPeminjam" style="height: 70px; object-fit: contain; margin-left: -5px;">
        </a>

        <ul class="sidebar-menu">
            <li>
                <a href="<?= URLROOT; ?>/dashboard" class="nav-link text-decoration-none <?= isActiveMenu('dashboard') ? 'active' : ''; ?>">
                    <i class="icon bi bi-grid fs-5"></i> Dashboard
                </a>
            </li>

            <?php if(hasRole([1])): ?>
            <li class="nav-item">
                <?php $isMasterDataActive = isActiveMenu('user') || isActiveMenu('category') || isActiveMenu('tool'); ?>
                <a class="nav-link text-decoration-none <?= $isMasterDataActive ? '' : 'collapsed'; ?>" data-bs-toggle="collapse" href="#masterDataCollapse" role="button" aria-expanded="<?= $isMasterDataActive ? 'true' : 'false'; ?>">
                    <i class="icon bi bi-server fs-5"></i>
                    <span class="flex-grow-1">Master Data</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </a>
                <div class="collapse <?= $isMasterDataActive ? 'show' : ''; ?>" id="masterDataCollapse">
                    <ul class="list-unstyled ps-4 ms-2 mt-1 mb-2 border-start">
                        <li>
                            <a href="<?= URLROOT; ?>/user" class="nav-link nav-link-sub text-decoration-none <?= isActiveMenu('user') ? 'active' : ''; ?>">
                                <i class="icon bi bi-people fs-6"></i> User
                            </a>
                        </li>
                        <li>
                            <a href="<?= URLROOT; ?>/category" class="nav-link nav-link-sub text-decoration-none <?= isActiveMenu('category') ? 'active' : ''; ?>">
                                <i class="icon bi bi-tags fs-6"></i> Kategori
                            </a>
                        </li>
                        <li>
                            <a href="<?= URLROOT; ?>/tool" class="nav-link nav-link-sub text-decoration-none <?= isActiveMenu('tool') ? 'active' : ''; ?>">
                                <i class="icon bi bi-box-seam fs-6"></i> Alat
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <li class="nav-item mt-2">
                <?php $isTransaksiActive = isActiveMenu('borrowing') || isActiveMenu('return'); ?>
                <a class="nav-link text-decoration-none <?= $isTransaksiActive ? '' : 'collapsed'; ?>" data-bs-toggle="collapse" href="#transaksiCollapse" role="button" aria-expanded="<?= $isTransaksiActive ? 'true' : 'false'; ?>">
                    <i class="icon bi bi-arrow-left-right fs-5"></i>
                    <span class="flex-grow-1">Transaksi</span>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </a>
                <div class="collapse <?= $isTransaksiActive ? 'show' : ''; ?>" id="transaksiCollapse">
                    <ul class="list-unstyled ps-4 ms-2 mt-1 mb-2 border-start">
                        <li>
                            <a href="<?= URLROOT; ?>/borrowing" class="nav-link nav-link-sub text-decoration-none <?= isActiveMenu('borrowing') ? 'active' : ''; ?>">
                                <i class="icon bi bi-journal-arrow-up fs-6"></i> Peminjaman
                            </a>
                        </li>
                        <li>
                            <a href="<?= URLROOT; ?>/return" class="nav-link nav-link-sub text-decoration-none <?= isActiveMenu('return') ? 'active' : ''; ?>">
                                <i class="icon bi bi-journal-arrow-down fs-6"></i> Pengembalian
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?= URLROOT; ?>/report" class="nav-link text-decoration-none <?= isActiveMenu('report') ? 'active' : ''; ?>">
                    <i class="icon bi bi-file-earmark-text fs-5"></i> Laporan
                </a>
            </li>

            <?php if(hasRole([1])): ?>
            <li class="nav-item">
                <a href="<?= URLROOT; ?>/activitylog" class="nav-link text-decoration-none <?= isActiveMenu('activitylog') ? 'active' : ''; ?>">
                    <i class="icon bi bi-clock-history fs-5"></i> Log Aktivitas
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= URLROOT; ?>/settings" class="nav-link text-decoration-none <?= isActiveMenu('settings') ? 'active' : ''; ?>">
                    <i class="icon bi bi-gear fs-5"></i> Pengaturan
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <ul class="sidebar-menu mt-auto">
            <li class="nav-item">
                <a href="<?= URLROOT; ?>/auth/logout" class="nav-link text-decoration-none">
                    <i class="icon bi bi-box-arrow-left fs-5"></i> Keluar
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header Admin -->
        <header class="top-header-admin d-print-none">
            <div class="d-flex align-items-center gap-3 flex-grow-1">
                <!-- Hamburger (mobile) -->
                <button class="btn btn-light d-lg-none border-0 p-2 rounded-3" id="sidebarToggle" type="button">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <!-- Search -->
                <form action="<?= URLROOT; ?>/search" method="GET" class="admin-search-form d-none d-md-flex align-items-center">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Cari alat, peminjaman, pengguna..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                </form>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php
                $userAvatarUrl = !empty($_SESSION['user_avatar'])
                    ? URLROOT . '/assets/img/avatars/' . $_SESSION['user_avatar']
                    : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name']) . '&background=1264E8&color=fff';
                ?>
                <div class="dropdown">
                    <div class="admin-user-trigger" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="admin-user-info d-none d-sm-flex">
                            <span class="admin-user-name"><?= htmlspecialchars($_SESSION['user_name']); ?></span>
                            <span class="admin-user-role"><?= getRoleName($_SESSION['user_role']); ?></span>
                        </div>
                        <img src="<?= $userAvatarUrl; ?>" class="admin-user-avatar" alt="Avatar">
                        <i class="bi bi-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="<?= URLROOT; ?>/profile"><i class="bi bi-person me-2 text-muted"></i>Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="<?= URLROOT; ?>/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-4 p-md-5">
            <?php flash('message'); ?>

<?php else: // ============ PEMINJAM (TOP NAVBAR) ============ ?>

<div class="peminjam-app">
    <nav class="peminjam-navbar">
        <div class="peminjam-nav-inner">
            <!-- Brand -->
            <a class="peminjam-navbar-brand" href="<?= isset($_SESSION['user_id']) ? URLROOT . '/dashboard' : URLROOT . '/'; ?>">
                <img src="<?= URLROOT; ?>/assets/img/logo-blue.png" alt="SiPeminjam" class="brand-logo-img">
                <span class="brand-copy"><strong>SiPeminjam</strong><small>Sistem Informasi Peminjaman Alat</small></span>
            </a>

            <!-- Mobile toggle -->
            <button class="mobile-nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPeminjam" aria-label="Buka menu"><i class="bi bi-list"></i></button>

            <!-- Nav links -->
            <div class="peminjam-nav-wrap" id="navbarPeminjam">
                <ul class="peminjam-nav">
                    <li><a href="<?= isset($_SESSION['user_id']) ? URLROOT . '/dashboard' : URLROOT . '/'; ?>" class="<?= (isActiveMenu('dashboard') || isActiveMenu('home') || empty($_GET['url'])) ? 'active' : ''; ?>">Beranda</a></li>
                    <li class="dropdown nav-dropdown">
                        <a href="#" class="<?= isActiveMenu('tool') ? 'active' : ''; ?>" data-bs-toggle="dropdown">Alat <i class="bi bi-chevron-down ms-1" style="font-size:10px;"></i></a>
                        <ul class="dropdown-menu design-dropdown shadow-sm border-0 mt-2">
                            <li><a class="dropdown-item py-2" href="<?= URLROOT; ?>/tool"><i class="bi bi-list-ul me-2 text-muted"></i>Daftar Alat</a></li>
                            <li><a class="dropdown-item py-2" href="<?= URLROOT; ?>/tool?category=all"><i class="bi bi-grid me-2 text-muted"></i>Kategori</a></li>
                        </ul>
                    </li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="dropdown nav-dropdown">
                        <a href="#" class="<?= isActiveMenu('borrowing') ? 'active' : ''; ?>" data-bs-toggle="dropdown">Peminjaman <i class="bi bi-chevron-down ms-1" style="font-size:10px;"></i></a>
                        <ul class="dropdown-menu design-dropdown">
                            <li><a class="dropdown-item" href="<?= URLROOT; ?>/borrowing/create"><i class="bi bi-journal-plus"></i>Ajukan Peminjaman</a></li>
                            <li><a class="dropdown-item" href="<?= URLROOT; ?>/borrowing"><i class="bi bi-journal-text"></i>Peminjaman Saya</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= URLROOT; ?>/return" class="<?= isActiveMenu('return') ? 'active' : ''; ?>">Pengembalian</a></li>
                    <li><a href="<?= URLROOT; ?>/borrowing" class="<?= isActiveMenu('borrowing') ? 'active' : ''; ?>">Riwayat</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Right: Search + Account -->
            <div class="peminjam-right">
                
                <?php if(isset($_SESSION['user_id'])): ?>

                <?php $userAvatarUrl = !empty($_SESSION['user_avatar']) ? URLROOT . '/assets/img/avatars/' . $_SESSION['user_avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name']) . '&background=1264E8&color=fff'; ?>
                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="peminjam-user-trigger" data-bs-toggle="dropdown">
                        <img src="<?= $userAvatarUrl; ?>" class="account-avatar" alt="Avatar" style="object-fit: cover;">
                        <span class="account-copy d-none d-lg-flex">
                            <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong>
                            <small><?= getRoleName($_SESSION['user_role']); ?></small>
                        </span>
                        <i class="bi bi-chevron-down account-chevron"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end design-dropdown account-menu">
                        <li><a class="dropdown-item" href="<?= URLROOT; ?>/profile"><i class="bi bi-person"></i>Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= URLROOT; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i>Keluar</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= URLROOT; ?>/auth" class="text-decoration-none text-navy fw-bold" style="font-size: 0.9rem;">Masuk</a>
                    <a href="<?= URLROOT; ?>/auth" class="btn btn-primary btn-sm px-4 rounded-pill" style="font-size: 0.85rem;">Daftar</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="peminjam-content">
        <div class="peminjam-page-shell">
            <?php flash('message'); ?>
<?php endif; ?>