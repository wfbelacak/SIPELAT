<?php
$file = 'app/helpers/helper.php';
$content = file_get_contents($file);

$oldToolImage = "function toolImage(\$toolOrName) {
    \$name = is_object(\$toolOrName) ? (\$toolOrName->name ?? '') : (string)\$toolOrName;
    \$name = strtolower(\$name);
    if (strpos(\$name, 'laptop') !== false || strpos(\$name, 'lenovo') !== false) return 'laptop.svg';
    if (strpos(\$name, 'proyektor') !== false || strpos(\$name, 'projector') !== false || strpos(\$name, 'epson') !== false) return 'projector.svg';
    if (strpos(\$name, 'kamera') !== false || strpos(\$name, 'camera') !== false || strpos(\$name, 'sony') !== false) return 'camera.svg';
    if (strpos(\$name, 'monitor') !== false || strpos(\$name, 'lcd') !== false) return 'monitor.svg';
    return 'box.svg';
}";

$newToolImage = "function toolImage(\$toolOrName) {
    if (is_object(\$toolOrName) && !empty(\$toolOrName->image)) {
        return \$toolOrName->image;
    }
    
    \$name = is_object(\$toolOrName) ? (\$toolOrName->name ?? '') : (string)\$toolOrName;
    \$name = strtolower(\$name);
    
    if (strpos(\$name, 'laptop') !== false || strpos(\$name, 'lenovo') !== false) return 'laptop.svg';
    if (strpos(\$name, 'proyektor') !== false || strpos(\$name, 'projector') !== false || strpos(\$name, 'epson') !== false) return 'projector.svg';
    if (strpos(\$name, 'kamera') !== false || strpos(\$name, 'camera') !== false || strpos(\$name, 'sony') !== false) return 'camera.svg';
    if (strpos(\$name, 'monitor') !== false || strpos(\$name, 'lcd') !== false) return 'monitor.svg';
    return 'box.svg';
}";

$content = str_replace($oldToolImage, $newToolImage, $content);
file_put_contents($file, $content);
echo "Helper updated.\n";
