<?php
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `gudang_tkj` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `gudang_tkj`");

    $schemaFile = __DIR__ . '/database/schema.sql';
    if (file_exists($schemaFile)) {
        $sql = file_get_contents($schemaFile);
        $pdo->exec($sql);
        echo "[OK] Schema database berhasil diimport!\n";
    }

    $seedFile = __DIR__ . '/database/seed.sql';
    if (file_exists($seedFile)) {
        $sqlSeed = file_get_contents($seedFile);
        $pdo->exec($sqlSeed);
        echo "[OK] Seed data sampel berhasil diimport!\n";
    }

} catch (PDOException $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
}
