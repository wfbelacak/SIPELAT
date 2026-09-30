<?php
$file = 'public/assets/css/design.css';
$css = file_get_contents($file);

// Ensure peminjam-nav-wrap is flex on desktop, overriding Bootstrap's .collapse
$css = str_replace(
    '.peminjam-nav-wrap {',
    '.peminjam-nav-wrap.collapse { display: flex !important; }' . "\n" . '.peminjam-nav-wrap {',
    $css
);

// Fix mobile breakpoints
$css = str_replace('@media (max-width: 768px) {', '@media (max-width: 991px) {', $css);

// Handle mobile menu display properly
$css = str_replace('.peminjam-nav-wrap { display: none; }', '.peminjam-nav-wrap { display: none !important; }
    .peminjam-nav-wrap.show { display: flex !important; flex-direction: column; position: absolute; top: 70px; left: 0; right: 0; background: var(--white); box-shadow: var(--shadow-md); height: auto; padding: 10px 0; z-index: 999; }
    .peminjam-nav { height: auto; flex-direction: column; }
    .peminjam-nav > li > a { height: 48px; width: 100%; justify-content: flex-start; padding: 0 24px; }', $css);

file_put_contents($file, $css);
echo "CSS updated.\n";
