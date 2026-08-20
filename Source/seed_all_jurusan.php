<?php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/auth.php';

$db = Database::getInstance()->getConnection();

$departments = [
    ['nama' => 'Teknik Komputer dan Jaringan (TKJ)', 'desk' => 'Jurusan Teknik Komputer dan Jaringan'],
    ['nama' => 'Desain Komunikasi Visual (DKV)', 'desk' => 'Jurusan Desain Komunikasi Visual'],
    ['nama' => 'Teknik Kendaraan Ringan (TKR)', 'desk' => 'Jurusan Teknik Kendaraan Ringan'],
    ['nama' => 'Desain Pemodelan dan Informasi Bangunan (DPIB)', 'desk' => 'Jurusan Desain Pemodelan dan Informasi Bangunan'],
    ['nama' => 'Teknik Konstruksi dan Perumahan (TKP)', 'desk' => 'Jurusan Teknik Konstruksi dan Perumahan'],
    ['nama' => 'Teknik Elektronika Industri (ELIND)', 'desk' => 'Jurusan Teknik Elektronika Industri'],
    ['nama' => 'Teknik Instalasi Tenaga Listrik (TITL)', 'desk' => 'Jurusan Teknik Instalasi Tenaga Listrik'],
    ['nama' => 'Teknik Pemesinan (PM)', 'desk' => 'Jurusan Teknik Pemesinan'],
    ['nama' => 'Teknik Pengelasan (Las)', 'desk' => 'Jurusan Teknik Pengelasan']
];

foreach ($departments as $dept) {
    // Check if exists
    $chk = $db->prepare("SELECT id FROM jurusan WHERE nama_jurusan LIKE :name LIMIT 1");
    $chk->execute([':name' => '%' . explode(' (', $dept['nama'])[0] . '%']);
    if (!$chk->fetch()) {
        $id = generateUuid();
        $stmt = $db->prepare("INSERT INTO jurusan (id, nama_jurusan, deskripsi) VALUES (:id, :nama, :desk)");
        $stmt->execute([
            ':id' => $id,
            ':nama' => $dept['nama'],
            ':desk' => $dept['desk']
        ]);
        echo "Added: " . $dept['nama'] . "\n";
    } else {
        echo "Already exists: " . $dept['nama'] . "\n";
    }
}

echo "ALL JURUSAN SEEDED SUCCESSFULLY\n";
