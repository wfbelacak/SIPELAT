<?php
$file = 'public/assets/css/design.css';
$css = file_get_contents($file);

// Change font to Inter or Plus Jakarta Sans
$css = str_replace('Roboto', 'Inter', $css);
$css = str_replace('https://fonts.googleapis.com/css2?family=Roboto', 'https://fonts.googleapis.com/css2?family=Inter', $css);

// Fix history tabs active state (solid blue)
$css = preg_replace(
    '/\.history-tabs a\.active\s*\{[^}]+\}/',
    ".history-tabs a.active { background: #1264e8 !important; color: #FFFFFF !important; box-shadow: 0 4px 6px -1px rgba(18,100,232,0.2) !important; }",
    $css
);

// Append specific exact styles
$css .= "
.text-cat { color: var(--text-muted); font-size: 13px; margin-bottom: 12px; }
.badge-status.tersedia { background: #dcf7e7; color: #13965a; border-radius: 20px; padding: 4px 12px; font-size: 12px; }
.badge-status.dipinjam { background: #e1efff; color: #2475d6; border-radius: 20px; padding: 4px 12px; font-size: 12px; }
.badge-status.maintenance { background: #fff1d5; color: #ad6b00; border-radius: 20px; padding: 4px 12px; font-size: 12px; }

.tool-card .tool-info { padding: 20px; }
.tool-card .tool-title { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
.tool-card .tool-code { font-size: 13px; color: var(--text-muted); margin-bottom: 4px; }
.tool-card .tool-cat { font-size: 13px; color: var(--text-muted); margin-bottom: 12px; }
.tool-card .tool-stock { color: var(--text-main); font-size: 13px; font-weight: 600; margin-bottom: 20px; margin-top: 12px; }
.tool-card .btn-outline-primary { border-radius: 6px; padding: 8px; font-weight: 600; border: 1px solid #1264e8; color: #1264e8; width: 100%; display: block; text-align: center; }

/* Filter Bar refinement */
.filter-bar .search-box input { border-radius: 8px; }
.filter-bar .filter-select { border-radius: 8px; }
.filter-bar .btn-filter { border-radius: 8px; font-weight: 600; }

/* Dashboard overrides */
.hero-dashboard { background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%); padding: 50px; }
.hero-text h2 { font-size: 32px; font-weight: 700; }
.hero-text p { font-size: 16px; color: #64748b; }
.stat-card { border-radius: 12px; padding: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }

/* Navbar refinement */
.peminjam-nav > li > a.active::after { height: 4px; background: #1264e8; border-radius: 4px 4px 0 0; }
.peminjam-nav > li > a { font-weight: 600; color: #64748b; }
.peminjam-nav > li > a.active { color: #1264e8; }
";

file_put_contents($file, $css);
echo "CSS refined.\n";
