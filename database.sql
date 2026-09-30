  -- CREATE DATABASE IF NOT EXISTS sistem_peminjaman_alat_v1;
  -- USE sistem_peminjaman_alat_v1;

  -- 1. Tabel roles
  CREATE TABLE `roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
    
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

  -- Insert default roles
  INSERT INTO `roles` (`id`, `name`, `description`) VALUES
  (1, 'Admin', 'Administrator sistem dengan hak akses penuh'),
  (2, 'Petugas', 'Petugas yang mengelola operasional peminjaman dan pengembalian'),
  (3, 'Peminjam', 'Pengguna yang meminjam alat');


  -- 2. Tabel users
  CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `role_id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `username` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `status` enum('AKTIF','NONAKTIF') DEFAULT 'AKTIF',
    `last_login` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_role_id_foreign` (`role_id`),
    CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

  -- Insert default admin user (password: admin123)
  INSERT INTO `users` (`role_id`, `name`, `username`, `email`, `password`) VALUES
  (1, 'Administrator', 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


  -- 3. Tabel categories
  CREATE TABLE `categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `status` enum('AKTIF','NONAKTIF') DEFAULT 'AKTIF',
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- 4. Tabel tools
  CREATE TABLE `tools` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `category_id` int(11) NOT NULL,
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `quantity` int(11) NOT NULL DEFAULT 0,
    `available_qty` int(11) NOT NULL DEFAULT 0,
    `condition` enum('BAIK','RUSAK_RINGAN','RUSAK_BERAT') DEFAULT 'BAIK',
    `location` varchar(100) DEFAULT NULL,
    `image` varchar(255) DEFAULT NULL,
    `status` enum('TERSEDIA','DIPINJAM','MAINTENANCE','RUSAK','TIDAK_AKTIF') DEFAULT 'TERSEDIA',
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `tools_code_unique` (`code`),
    KEY `tools_category_id_foreign` (`category_id`),
    CONSTRAINT `tools_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- 5. Tabel borrowings
  CREATE TABLE `borrowings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(50) NOT NULL,
    `user_id` int(11) NOT NULL,
    `approved_by` int(11) DEFAULT NULL,
    `borrow_date` date NOT NULL,
    `due_date` date NOT NULL,
    `purpose` text DEFAULT NULL,
    `status` enum('DRAFT','MENUNGGU','DISETUJUI','DITOLAK','DIPINJAM','DIKEMBALIKAN','SELESAI','DIBATALKAN') DEFAULT 'DRAFT',
    `notes` text DEFAULT NULL,
    `approved_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `borrowings_code_unique` (`code`),
    KEY `borrowings_user_id_foreign` (`user_id`),
    KEY `borrowings_approved_by_foreign` (`approved_by`),
    CONSTRAINT `borrowings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- 6. Tabel borrowing_details
  CREATE TABLE `borrowing_details` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `borrowing_id` int(11) NOT NULL,
    `tool_id` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `notes` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `borrowing_details_borrowing_id_foreign` (`borrowing_id`),
    KEY `borrowing_details_tool_id_foreign` (`tool_id`),
    CONSTRAINT `borrowing_details_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `borrowing_details_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- 7. Tabel returns
  CREATE TABLE `returns` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `borrowing_id` int(11) NOT NULL,
    `received_by` int(11) DEFAULT NULL,
    `return_date` date NOT NULL,
    `late_days` int(11) DEFAULT 0,
    `total_fine` decimal(10,2) DEFAULT 0.00,
    `status` enum('DIAJUKAN','DITERIMA','DIPERIKSA','SELESAI') DEFAULT 'DIAJUKAN',
    `notes` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `returns_borrowing_id_unique` (`borrowing_id`),
    KEY `returns_received_by_foreign` (`received_by`),
    CONSTRAINT `returns_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON UPDATE CASCADE,
    CONSTRAINT `returns_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- 8. Tabel return_details
  CREATE TABLE `return_details` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `return_id` int(11) NOT NULL,
    `tool_id` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `condition` enum('BAIK','RUSAK_RINGAN','RUSAK_BERAT','HILANG') DEFAULT 'BAIK',
    `damage_note` text DEFAULT NULL,
    `fine` decimal(10,2) DEFAULT 0.00,
    PRIMARY KEY (`id`),
    KEY `return_details_return_id_foreign` (`return_id`),
    KEY `return_details_tool_id_foreign` (`tool_id`),
    CONSTRAINT `return_details_return_id_foreign` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `return_details_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- 9. Tabel activity_logs
  CREATE TABLE `activity_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `action` varchar(50) NOT NULL,
    `module` varchar(50) NOT NULL,
    `reference_id` int(11) DEFAULT NULL,
    `description` text NOT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `activity_logs_user_id_foreign` (`user_id`),
    CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


  -- STORED FUNCTION: Hitung Denda
  DELIMITER $$
  CREATE FUNCTION `fn_hitung_denda` (tanggal_jatuh_tempo DATE, tanggal_kembali DATE, tarif_denda DECIMAL(10,2)) 
  RETURNS DECIMAL(10,2)
  DETERMINISTIC
  BEGIN
      DECLARE jumlah_hari INT;
      DECLARE total_denda DECIMAL(10,2);
      
      SET jumlah_hari = DATEDIFF(tanggal_kembali, tanggal_jatuh_tempo);
      
      IF jumlah_hari > 0 THEN
          SET total_denda = jumlah_hari * tarif_denda;
      ELSE
          SET total_denda = 0;
      END IF;
      
      RETURN total_denda;
  END$$
  DELIMITER ;