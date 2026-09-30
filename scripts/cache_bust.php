<?php
$file = 'app/views/layouts/header.php';
$content = file_get_contents($file);

// Cache busting design.css
$content = preg_replace(
    '/(design\.css\?v=)\d+/',
    '${1}' . time(),
    $content
);

file_put_contents($file, $content);
echo "Header cache busted.\n";
