<?php
$file = 'app/views/dashboard/index.php';
$content = file_get_contents($file);

// Find the corrupted part where the admin table was cut off
$badCut = "                        <?php else: ?>\n    <section class=\"hero-dashboard\">";
$badCut2 = "                        <?php else: ?>\r\n    <section class=\"hero-dashboard\">";

$fix = "                        <?php else: ?>
                            <tr><td colspan=\"5\" class=\"text-center text-muted py-4\">Belum ada peminjaman baru</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
    <section class=\"hero-dashboard\">";

if (strpos($content, "<?php else: ?>\n    <section class=\"hero-dashboard\">") !== false) {
    $content = str_replace("<?php else: ?>\n    <section class=\"hero-dashboard\">", $fix, $content);
} elseif (strpos($content, "<?php else: ?>\r\n    <section class=\"hero-dashboard\">") !== false) {
    $content = str_replace("<?php else: ?>\r\n    <section class=\"hero-dashboard\">", $fix, $content);
} else {
    // try to fix dynamically
    $pattern = '/<\?php else: \?>\s*<section class="hero-dashboard">/';
    $content = preg_replace($pattern, $fix, $content);
}

// Ensure footer is present
if (strpos($content, "<?php \$this->view('layouts/footer'); ?>") === false) {
    $content .= "\n<?php \$this->view('layouts/footer'); ?>\n";
}

file_put_contents($file, $content);
echo "Dashboard fixed.\n";
