<?php
require_once __DIR__ . '/app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Add bukti_foto to peminjaman
    $cols = $db->query("SHOW COLUMNS FROM peminjaman LIKE 'bukti_foto'")->fetchAll();
    if (count($cols) === 0) {
        $db->exec("ALTER TABLE peminjaman ADD COLUMN bukti_foto VARCHAR(255) NULL AFTER catatan");
        echo "Column bukti_foto added to table peminjaman!\n";
    } else {
        echo "Column bukti_foto already exists in table peminjaman.\n";
    }
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
