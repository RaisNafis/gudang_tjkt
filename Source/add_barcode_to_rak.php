<?php
// add_barcode_to_rak.php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/auth.php';

try {
    $db = Database::getInstance()->getConnection();
    if (!$db) {
        die("Koneksi DB gagal.\n");
    }

    echo "Memulai migrasi kolom barcode pada tabel rak...\n";

    // 1. Cek apakah kolom barcode sudah ada
    $cols = $db->query("SHOW COLUMNS FROM `rak` LIKE 'barcode'")->fetchAll();
    if (count($cols) === 0) {
        $db->exec("ALTER TABLE `rak` ADD COLUMN `barcode` VARCHAR(100) NULL AFTER `nama_rak`");
        echo "Kolom `barcode` berhasil ditambahkan ke tabel `rak`!\n";
    } else {
        echo "Kolom `barcode` sudah ada di tabel `rak`.\n";
    }

    // 2. Isi barcode yang kosong pada data rak
    $raks = $db->query("SELECT id, barcode FROM `rak` WHERE barcode IS NULL OR barcode = '' ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
    $counter = 1;

    // Ambil max barcode rak jika ada
    $stmtMax = $db->query("SELECT barcode FROM `rak` WHERE barcode LIKE '8993%' ORDER BY barcode DESC LIMIT 1");
    $maxBarcode = $stmtMax->fetchColumn();
    if ($maxBarcode && preg_match('/^8993(\d+)$/', $maxBarcode, $matches)) {
        $counter = intval($matches[1]) + 1;
    }

    $stmtUpd = $db->prepare("UPDATE `rak` SET `barcode` = :code WHERE id = :id");
    foreach ($raks as $r) {
        $code = '8993' . str_pad($counter++, 8, '0', STR_PAD_LEFT);
        $stmtUpd->execute([':code' => $code, ':id' => $r['id']]);
        echo "Rak ID {$r['id']} diupdate barcode: {$code}\n";
    }

    echo "Migrasi barcode rak selesai!\n";
} catch (Exception $e) {
    echo "Error migrasi: " . $e->getMessage() . "\n";
}
