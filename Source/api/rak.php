<?php
/**
 * REST API Endpoint for Info Rak & Barang di Dalamnya
 * URL: GET /api/rak.php?id={RAK_ID}
 */
ob_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/Rak.php';

$id = $_GET['id'] ?? null;
$jurusanId = $_GET['jurusan_id'] ?? null;

if (!empty($id)) {
    $rak = Rak::getById($id);
    if ($rak) {
        $items = Rak::getItemsInRak($id);
        $rak['total_barang'] = count($items);
        $rak['barang_list'] = $items;
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Detail rak dan daftar barang berhasil diambil',
            'data' => $rak
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Data rak tidak ditemukan'
        ]);
    }
    exit;
}

// Fetch all shelves with items
$allRak = Rak::getAll($jurusanId);
foreach ($allRak as &$r) {
    $r['barang_list'] = Rak::getItemsInRak($r['id']);
}
unset($r);

http_response_code(200);
echo json_encode([
    'success' => true,
    'total' => count($allRak),
    'message' => 'Daftar rak beserta barang berhasil diambil',
    'data' => $allRak
]);
