<?php
// migrate_rak.php - Auto Migration script to create table rak and add rak_id to barang
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/auth.php';

try {
    $db = Database::getInstance()->getConnection();
    if (!$db) {
        die("Koneksi database gagal.\n");
    }

    echo "Memulai migrasi tabel rak...\n";

    // 1. Buat Tabel rak jika belum ada
    $sqlRak = "
        CREATE TABLE IF NOT EXISTS `rak` (
            `id` VARCHAR(36) NOT NULL PRIMARY KEY,
            `jurusan_id` VARCHAR(36) DEFAULT NULL,
            `nama_rak` VARCHAR(255) NOT NULL,
            `kategori_rak` VARCHAR(255) DEFAULT NULL,
            `keterangan` TEXT DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT `fk_rak_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $db->exec($sqlRak);
    echo "[OK] Tabel 'rak' berhasil dibuat / diverifikasi.\n";

    // 2. Tambah kolom rak_id pada tabel barang jika belum ada
    $checkCol = $db->query("SHOW COLUMNS FROM `barang` LIKE 'rak_id'");
    if ($checkCol->rowCount() === 0) {
        $db->exec("ALTER TABLE `barang` ADD COLUMN `rak_id` VARCHAR(36) DEFAULT NULL AFTER `kategori_id`");
        echo "[OK] Kolom 'rak_id' berhasil ditambahkan ke tabel 'barang'.\n";
    } else {
        echo "[INFO] Kolom 'rak_id' sudah ada pada tabel 'barang'.\n";
    }

    // 3. Seed data contoh Rak jika tabel rak masih kosong
    $countRak = $db->query("SELECT COUNT(*) FROM `rak`")->fetchColumn();
    if ($countRak == 0) {
        $jurusans = $db->query("SELECT id FROM `jurusan` LIMIT 3")->fetchAll(PDO::FETCH_COLUMN);
        $j1 = $jurusans[0] ?? null;
        $j2 = $jurusans[1] ?? null;

        $sampleRaks = [
            [
                'id' => generateUuid(),
                'jurusan_id' => $j1,
                'nama_rak' => 'Rak A1 - Jaringan',
                'kategori_rak' => 'Networking & Router',
                'keterangan' => 'Tempat penyimpanan router, switch, dan crimping tool'
            ],
            [
                'id' => generateUuid(),
                'jurusan_id' => $j1,
                'nama_rak' => 'Rak A2 - Kabel & Aksesoris',
                'kategori_rak' => 'Kabel UTP & Connector',
                'keterangan' => 'Tempat penyimpanan roll kabel UTP, LAN tester, dan RJ45'
            ],
            [
                'id' => generateUuid(),
                'jurusan_id' => $j2,
                'nama_rak' => 'Rak B1 - Komputer & Hardware',
                'kategori_rak' => 'Komponen PC',
                'keterangan' => 'Penyimpanan PC, motherboard, RAM, dan power supply'
            ]
        ];

        $insStmt = $db->prepare("INSERT INTO `rak` (id, jurusan_id, nama_rak, kategori_rak, keterangan) VALUES (:id, :jid, :nama, :kat, :ket)");
        foreach ($sampleRaks as $r) {
            $insStmt->execute([
                ':id' => $r['id'],
                ':jid' => $r['jurusan_id'],
                ':nama' => $r['nama_rak'],
                ':kat' => $r['kategori_rak'],
                ':ket' => $r['keterangan']
            ]);
        }
        echo "[OK] 3 Sample data Rak berhasil dimasukkan!\n";

        // Assign rak ke beberapa barang pertama
        $raks = $db->query("SELECT id FROM `rak` LIMIT 2")->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($raks[0])) {
            $db->exec("UPDATE `barang` SET `rak_id` = '{$raks[0]}' WHERE `rak_id` IS NULL LIMIT 3");
            echo "[OK] Mengaitkan beberapa barang ke sampel Rak A1.\n";
        }
    }

    echo "Migrasi Rak selesai dengan sukses!\n";
} catch (Exception $e) {
    echo "Error Migrasi: " . $e->getMessage() . "\n";
}
