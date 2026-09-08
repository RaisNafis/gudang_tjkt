<?php
// add_guru_peminjam_column.php
require_once __DIR__ . '/app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    if (!$db) throw new Exception("Koneksi gagal");

    $db->exec("ALTER TABLE peminjaman ADD COLUMN guru_peminjam VARCHAR(255) NULL AFTER pengguna_id");
    echo "COLUMN_ADDED_SUCCESSFULLY\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "COLUMN_ALREADY_EXISTS\n";
    } else {
        echo "MIGRATION_ERROR: " . $e->getMessage() . "\n";
    }
}
