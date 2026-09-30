<?php
$file = 'app/views/tools/index.php';
$content = file_get_contents($file);

// Add Add Tool button for admins
$headerOrig = '<div class="section-header">
    <h3>Daftar Alat</h3>
</div>';

$headerNew = '<div class="section-header d-flex justify-content-between align-items-center">
    <h3>Daftar Alat</h3>
    <?php if(hasRole([1,2])): ?>
        <a href="<?= URLROOT; ?>/tool/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Alat</a>
    <?php endif; ?>
</div>';

$content = str_replace($headerOrig, $headerNew, $content);

// Add Edit/Delete buttons to tool cards for admins
$btnOrig = '<a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn-outline-primary mt-auto">Detail</a>';
$btnNew = '
            <?php if(hasRole([1,2])): ?>
                <div class="mt-auto d-flex gap-2">
                    <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn-outline-primary flex-grow-1 text-center" style="padding:0.4rem;">Detail</a>
                    <a href="<?= URLROOT; ?>/tool/edit/<?= $tool->id; ?>" class="btn btn-warning btn-sm text-white" style="display:flex;align-items:center;padding:0.4rem;"><i class="bi bi-pencil-square"></i></a>
                    <form action="<?= URLROOT; ?>/tool/delete/<?= $tool->id; ?>" method="POST" class="m-0" onsubmit="return confirm(\'Yakin hapus alat ini?\');">
                        <button type="submit" class="btn btn-danger btn-sm h-100" style="padding:0.4rem;"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            <?php else: ?>
                <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn-outline-primary mt-auto text-center" style="padding:0.4rem;">Detail</a>
            <?php endif; ?>
';

$content = str_replace($btnOrig, $btnNew, $content);

file_put_contents($file, $content);
echo "tools/index.php updated with admin buttons.\n";
