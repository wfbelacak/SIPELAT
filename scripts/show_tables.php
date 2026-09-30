<?php
$pdo = new PDO('mysql:host=localhost;dbname=sistem_peminjaman_alat_v2','root','');
foreach($pdo->query('SHOW TABLES') as $row) {
    echo $row[0] . "\n";
}
