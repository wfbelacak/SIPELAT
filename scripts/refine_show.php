<?php
$file = 'app/views/tools/show.php';
$content = file_get_contents($file);

// Replace Kategori output
$content = str_replace(
    '<div class="stat-item"><i class="bi bi-grid text-muted"></i> Kategori : <strong><?= htmlspecialchars($tool->category_name ?? \'Umum\'); ?></strong></div>',
    '<div class="stat-item"><i class="bi bi-grid text-muted"></i> Kategori : <strong class="text-cat mb-0" style="font-size:14px; color:var(--text-main);">@<?= htmlspecialchars($tool->category_name ?? \'Umum\'); ?></strong></div>',
    $content
);

// Replace badges 
$content = str_replace(
    '<span class="badge bg-success ms-1"><i class="bi bi-hand-thumbs-up me-1"></i>',
    '<span class="badge-status tersedia ms-1">',
    $content
);

file_put_contents($file, $content);
echo "Show tool refined.\n";
