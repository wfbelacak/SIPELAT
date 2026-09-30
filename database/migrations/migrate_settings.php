<?php
$pdo = new PDO('mysql:host=localhost;dbname=sistem_peminjaman_alat_v2', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_group VARCHAR(50) DEFAULT 'general',
    description VARCHAR(255)
)";
$pdo->exec($sql);

// Seed default data
$defaultSettings = [
    ['app_name', 'SiPeminjam', 'general', 'Nama Aplikasi (Tampil di Header)'],
    ['instansi_name', 'Universitas Teknologi Cerdas', 'general', 'Nama Instansi (Kop Surat)'],
    ['instansi_address', 'Jl. Pendidikan No. 123, Kota Cerdas', 'general', 'Alamat Instansi (Kop Surat)'],
    ['instansi_contact', 'Telp: (021) 12345678 | Email: admin@instansi.ac.id', 'general', 'Kontak Instansi (Kop Surat)'],
    ['max_borrow_days', '7', 'borrowing', 'Batas Maksimal Hari Peminjaman'],
    ['fine_per_day', '10000', 'borrowing', 'Denda Keterlambatan per Hari (Rp)']
];

$stmt = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_group, description) VALUES (?, ?, ?, ?)");
foreach($defaultSettings as $s) {
    $stmt->execute($s);
}

echo "Tabel settings berhasil dibuat dan diisi data default.";
