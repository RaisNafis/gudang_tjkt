<?php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/auth.php';

$db = Database::getInstance()->getConnection();

// 1. Create table jurusan
$db->exec("CREATE TABLE IF NOT EXISTS jurusan (
    id CHAR(36) NOT NULL,
    kode_jurusan VARCHAR(50) NOT NULL UNIQUE,
    nama_jurusan VARCHAR(150) NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 2. Seed default jurusan if empty
$stmt = $db->query("SELECT COUNT(*) FROM jurusan");
if ($stmt->fetchColumn() == 0) {
    $tkjId = generateUuid();
    $dkvId = generateUuid();
    $rplId = generateUuid();
    
    $ins = $db->prepare("INSERT INTO jurusan (id, kode_jurusan, nama_jurusan, deskripsi) VALUES (?, ?, ?, ?)");
    $ins->execute([$tkjId, 'JUR-001', 'Teknik Komputer & Jaringan (TKJ)', 'Komputer, Hardware, Server, dan Jaringan']);
    $ins->execute([$dkvId, 'JUR-002', 'Desain Komunikasi Visual (DKV)', 'Kamera, Multimedia, Desain, dan Peralatan Studio']);
    $ins->execute([$rplId, 'JUR-003', 'Rekayasa Perangkat Lunak (RPL)', 'Perangkat Lunak, Server Testing, dan IoT Development']);
}

// Get TKJ id for default assignment
$tkjId = $db->query("SELECT id FROM jurusan WHERE kode_jurusan = 'JUR-001' LIMIT 1")->fetchColumn();

// 3. Add jurusan_id to all tables
$tables = ['pengguna', 'kategori', 'barang', 'barang_masuk', 'barang_keluar', 'peminjaman', 'log_aktivitas'];
foreach ($tables as $tbl) {
    $db->exec("ALTER TABLE {$tbl} ADD COLUMN IF NOT EXISTS jurusan_id CHAR(36) NULL AFTER id");
    if ($tkjId) {
        $db->exec("UPDATE {$tbl} SET jurusan_id = '{$tkjId}' WHERE jurusan_id IS NULL");
    }
}

echo "JURUSAN MIGRATION COMPLETED SUCCESSFULLY\n";
