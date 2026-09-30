<?php
$file = 'app/views/layouts/header.php';
$content = file_get_contents($file);

// Replace Alat link with a dropdown
$alatOrig = '<li><a href="<?= URLROOT; ?>/tool" class="<?= isActiveMenu(\'tool\') ? \'active\' : \'\'; ?>">Alat</a></li>';
$alatNew = '<li class="dropdown nav-dropdown">
    <a href="#" class="<?= isActiveMenu(\'tool\') ? \'active\' : \'\'; ?>" data-bs-toggle="dropdown">Alat <i class="bi bi-chevron-down ms-1" style="font-size:10px;"></i></a>
    <ul class="dropdown-menu design-dropdown shadow-sm border-0 mt-2">
        <li><a class="dropdown-item py-2" href="<?= URLROOT; ?>/tool"><i class="bi bi-list-ul me-2 text-muted"></i>Daftar Alat</a></li>
        <li><a class="dropdown-item py-2" href="<?= URLROOT; ?>/tool?category=all"><i class="bi bi-grid me-2 text-muted"></i>Kategori</a></li>
    </ul>
</li>';

$content = str_replace($alatOrig, $alatNew, $content);

// Ensure chevron down is styled properly on Peminjaman dropdown
$peminjamanOrig = 'data-bs-toggle="dropdown">Peminjaman <i class="bi bi-chevron-down"></i></a>';
$peminjamanNew = 'data-bs-toggle="dropdown">Peminjaman <i class="bi bi-chevron-down ms-1" style="font-size:10px;"></i></a>';
$content = str_replace($peminjamanOrig, $peminjamanNew, $content);

// Cache busting design.css again
$content = preg_replace(
    '/(design\.css\?v=)\d+/',
    '${1}' . time(),
    $content
);

file_put_contents($file, $content);
echo "Header refined.\n";
