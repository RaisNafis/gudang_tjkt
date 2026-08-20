<?php
require_once __DIR__ . '/app/config/database.php';

$db = Database::getInstance()->getConnection();

// 1. Convert database and all tables to utf8mb4_unicode_ci
$tables = ['jurusan', 'pengguna', 'kategori', 'barang', 'barang_masuk', 'barang_keluar', 'peminjaman', 'log_aktivitas'];

foreach ($tables as $tbl) {
    try {
        $db->exec("ALTER TABLE {$tbl} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    } catch (Exception $e) {
        echo "Warning on table {$tbl}: " . $e->getMessage() . "\n";
    }
}

// 2. Drop or make nullable kode columns if they exist
try {
    $db->exec("ALTER TABLE jurusan MODIFY COLUMN kode_jurusan VARCHAR(50) NULL");
    $db->exec("ALTER TABLE kategori MODIFY COLUMN kode_kategori VARCHAR(50) NULL");
    $db->exec("ALTER TABLE barang MODIFY COLUMN kode_barang VARCHAR(50) NULL");
} catch (Exception $e) {
    echo "Warning modifying kode columns: " . $e->getMessage() . "\n";
}

echo "COLLATION AND TABLE SCHEMA UPDATED SUCCESSFULLY\n";
