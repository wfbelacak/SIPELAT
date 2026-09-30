<?php
$pdo = new PDO('mysql:host=localhost;dbname=sistem_peminjaman_alat_v2', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Cek kolom avatar
$stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'avatar'");
if ($stmt->rowCount() == 0) {
    $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL DEFAULT NULL AFTER password");
    echo "Kolom avatar berhasil ditambahkan.\n";
} else {
    echo "Kolom avatar sudah ada.\n";
}
