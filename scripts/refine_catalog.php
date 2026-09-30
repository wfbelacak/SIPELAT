<?php
$file = 'app/views/tools/index.php';
$content = file_get_contents($file);

// Replace tool card HTML
$pattern = '/<div class="tool-info">.*?<\/div>\s*<\/article>/s';
$replacement = '<div class="tool-info">
            <h4 class="tool-title"><?= htmlspecialchars($tool->name); ?></h4>
            <div class="tool-code">Kode : <?= htmlspecialchars($tool->code); ?></div>
            <div class="tool-cat">@<?= htmlspecialchars($tool->category_name ?? \'Umum\'); ?></div>
            
            <div class="mt-2 mb-2">
            <?php if($tool->available_qty > 0 && $tool->status === \'TERSEDIA\'): ?>
                <span class="badge-status tersedia">Tersedia</span>
            <?php elseif($tool->status === \'MAINTENANCE\'): ?>
                <span class="badge-status maintenance">Maintenance</span>
            <?php else: ?>
                <span class="badge-status dipinjam"><?= htmlspecialchars(ucwords(strtolower(str_replace(\'_\',\' \',$tool->status)))); ?></span>
            <?php endif; ?>
            </div>
            
            <div class="tool-stock <?= $tool->available_qty <= 0 ? \'text-danger\' : \'\'; ?>">
                Stok : <?= (int)$tool->available_qty; ?> unit
            </div>
            
            <a href="<?= URLROOT; ?>/tool/show/<?= $tool->id; ?>" class="btn-outline-primary mt-auto">Detail</a>
        </div>
    </article>';

$content = preg_replace($pattern, $replacement, $content);

file_put_contents($file, $content);
echo "Tools index refined.\n";
