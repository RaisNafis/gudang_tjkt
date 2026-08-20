<?php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/auth.php';

$db = Database::getInstance()->getConnection();

try {
    // 1. Add warna_tema column to jurusan table if not exists
    $chk = $db->query("SHOW COLUMNS FROM jurusan LIKE 'warna_tema'");
    if (!$chk->fetch()) {
        $db->exec("ALTER TABLE jurusan ADD COLUMN warna_tema VARCHAR(50) NOT NULL DEFAULT 'kuning'");
        echo "Column warna_tema added successfully.\n";
    } else {
        echo "Column warna_tema already exists.\n";
    }

    // 2. Update default warna_tema based on department names
    $updates = [
        'TKJ' => 'kuning',
        'DKV' => 'kuning',
        'TKR' => 'orange',
        'DPIB' => 'orange',
        'TKP' => 'orange',
        'ELIND' => 'hijau',
        'TITL' => 'hijau',
        'PM' => 'merah',
        'Las' => 'merah'
    ];

    foreach ($updates as $code => $color) {
        $stmt = $db->prepare("UPDATE jurusan SET warna_tema = :color WHERE nama_jurusan LIKE :code");
        $stmt->execute([':color' => $color, ':code' => '%' . $code . '%']);
    }

    echo "UPDATED JURUSAN THEME COLORS SUCCESSFULLY\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
