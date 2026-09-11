<?php
// migrate_rak_lemari.php
require_once __DIR__ . '/app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    if (!$db) {
        die("Koneksi DB gagal.\n");
    }

    echo "Memulai migrasi jenis pada tabel rak dan barang...\n";

    // 1. Tambah kolom jenis pada tabel rak
    $colsRak = $db->query("SHOW COLUMNS FROM `rak` LIKE 'jenis'")->fetchAll();
    if (count($colsRak) === 0) {
        $db->exec("ALTER TABLE `rak` ADD COLUMN `jenis` VARCHAR(20) NOT NULL DEFAULT 'rak' AFTER `nama_rak`");
        echo "[OK] Kolom `jenis` berhasil ditambahkan ke tabel `rak`.\n";
    } else {
        echo "[INFO] Kolom `jenis` sudah ada di tabel `rak`.\n";
    }

    // 2. Tambah kolom jenis_rak pada tabel barang
    $colsBarang = $db->query("SHOW COLUMNS FROM `barang` LIKE 'jenis_rak'")->fetchAll();
    if (count($colsBarang) === 0) {
        $db->exec("ALTER TABLE `barang` ADD COLUMN `jenis_rak` VARCHAR(20) DEFAULT NULL AFTER `rak_id`");
        echo "[OK] Kolom `jenis_rak` berhasil ditambahkan ke tabel `barang`.\n";
    } else {
        echo "[INFO] Kolom `jenis_rak` sudah ada di tabel `barang`.\n";
    }

    // 3. Update existing records in rak based on nama_rak
    $db->exec("UPDATE `rak` SET `jenis` = 'lemari' WHERE LOWER(`nama_rak`) LIKE '%lemari%'");
    $db->exec("UPDATE `rak` SET `jenis` = 'rak' WHERE `jenis` IS NULL OR `jenis` = ''");

    // 4. Update barang jenis_rak based on linked rak
    $db->exec("UPDATE `barang` b JOIN `rak` r ON b.rak_id = r.id SET b.jenis_rak = r.jenis WHERE b.rak_id IS NOT NULL");

    echo "Migrasi selesai dengan sukses!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
