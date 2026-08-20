<?php
require_once __DIR__ . '/config/database.php';
$db = getDb();
$cols = $db->query('DESCRIBE peminjaman')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cols, JSON_PRETTY_PRINT);
