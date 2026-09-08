<?php
// add_guru_siswa_tables.php
require_once __DIR__ . '/app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    if (!$db) {
        throw new Exception("Koneksi database gagal.");
    }

    // 1. Tabel Guru (nama, mengajar: bengkel/umum, jurusan_id)
    $db->exec("
        CREATE TABLE IF NOT EXISTS `guru` (
            `id` VARCHAR(36) NOT NULL PRIMARY KEY,
            `nama_guru` VARCHAR(255) NOT NULL,
            `mengajar` ENUM('bengkel', 'umum') NOT NULL DEFAULT 'bengkel',
            `jurusan_id` VARCHAR(36) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT `fk_guru_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Tabel Siswa (nama, token 5 karakter, kelas, jurusan_id, tahun_ajaran)
    $db->exec("
        CREATE TABLE IF NOT EXISTS `siswa` (
            `id` VARCHAR(36) NOT NULL PRIMARY KEY,
            `nama_siswa` VARCHAR(255) NOT NULL,
            `token` VARCHAR(50) NOT NULL UNIQUE,
            `kelas` VARCHAR(50) NOT NULL,
            `jurusan_id` VARCHAR(36) DEFAULT NULL,
            `tahun_ajaran` VARCHAR(20) DEFAULT '2025/2026',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT `fk_siswa_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 3. Tambahkan kolom status_pengguna, token, guru_id, siswa_id pada tabel pengguna
    $addColumnIfNotExists = function($table, $column, $definition) use ($db) {
        $check = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if ($check->rowCount() === 0) {
            $db->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            echo "Added column $column to $table\n";
        }
    };

    $addColumnIfNotExists('pengguna', 'status_pengguna', "ENUM('tidak_ada', 'guru', 'siswa') NOT NULL DEFAULT 'tidak_ada'");
    $addColumnIfNotExists('pengguna', 'token', "VARCHAR(50) DEFAULT NULL");
    $addColumnIfNotExists('pengguna', 'guru_id', "VARCHAR(36) DEFAULT NULL");
    $addColumnIfNotExists('pengguna', 'siswa_id', "VARCHAR(36) DEFAULT NULL");

    echo "MIGRATION_SUCCESSFUL\n";
} catch (Exception $e) {
    echo "MIGRATION_ERROR: " . $e->getMessage() . "\n";
}
