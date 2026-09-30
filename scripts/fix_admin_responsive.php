<?php
$file = 'app/views/dashboard/index.php';
$content = file_get_contents($file);
// Replace col-sm-6 col-xl-3 with col-12 col-md-6 col-lg-3 to ensure 4 columns on lg screens (>= 992px)
$content = str_replace('col-sm-6 col-xl-3', 'col-12 col-md-6 col-xl-3', $content); // wait, let's just make it lg-3
$content = str_replace('col-12 col-md-6 col-xl-3', 'col-12 col-md-6 col-lg-3', $content); // in case I already ran it
$content = str_replace('col-sm-6 col-xl-3', 'col-12 col-md-6 col-lg-3', $content);
file_put_contents($file, $content);

$file2 = 'public/assets/css/style.css';
$content2 = file_get_contents($file2);

$responsiveCSS = "
/* Responsive adjustments for Admin Dashboard Cards on medium screens */
@media (min-width: 992px) and (max-width: 1399px) {
    .card-body.p-4 { padding: 1.25rem !important; }
    .stat-value { font-size: 1.75rem !important; }
    .text-uppercase.small { font-size: 0.65rem !important; margin-bottom: 0.25rem !important; }
    .icon-box { width: 40px !important; height: 40px !important; font-size: 1.25rem !important; }
    .trend-indicator { font-size: 0.7rem !important; padding: 0.2rem 0.4rem !important; }
    .card .d-flex.align-items-center.mt-3 { flex-wrap: wrap; gap: 4px; }
}
";

if (strpos($content2, '/* Responsive adjustments for Admin') === false) {
    $content2 .= $responsiveCSS;
    file_put_contents($file2, $content2);
    echo "CSS added.\n";
} else {
    echo "CSS already exists.\n";
}
