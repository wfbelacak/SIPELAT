<?php
$file = 'app/views/layouts/header.php';
$content = file_get_contents($file);

// Cache busting style.css
$content = preg_replace(
    '/(style\.css\?v=)\d+/',
    '${1}' . time(),
    $content
);

// If it doesn't have ?v= yet, add it
if (strpos($content, 'style.css?v=') === false) {
    $content = str_replace('style.css', 'style.css?v=' . time(), $content);
}

file_put_contents($file, $content);
echo "Header cache busted for style.css.\n";
