<?php
require_once __DIR__ . '/app/config/database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, nama_jurusan, warna_tema FROM jurusan");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows, JSON_PRETTY_PRINT) . "\n";
