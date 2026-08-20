<?php
require_once __DIR__ . '/app/config/database.php';

$db = Database::getInstance()->getConnection();

try {
    // 1. Modify peran column to handle admin_sekolah, admin_jurusan, petugas, siswa
    $db->exec("ALTER TABLE pengguna MODIFY COLUMN peran VARCHAR(50) NOT NULL DEFAULT 'siswa'");

    // 2. Update existing admin users:
    // Make 'admin' user an 'admin_sekolah' so testing all features is easy, or convert generic 'admin' to 'admin_jurusan'
    $db->exec("UPDATE pengguna SET peran = 'admin_sekolah' WHERE nama_pengguna = 'admin' OR peran = 'admin_sekolah'");
    $db->exec("UPDATE pengguna SET peran = 'admin_jurusan' WHERE peran IN ('admin', 'administrator') AND nama_pengguna != 'admin'");

    echo "ROLE MIGRATION SUCCESSFUL\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
