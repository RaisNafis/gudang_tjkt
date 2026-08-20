<?php
/**
 * REST API Endpoint for Info Barang
 * URL: GET /api/barang.php?id={BARANG_ID}
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
require_once __DIR__ . '/../app/models/Barang.php';

$id = $_GET['id'] ?? null;
$jurusanId = $_GET['jurusan_id'] ?? null;
$search = $_GET['search'] ?? null;

if (!empty($id)) {
    $item = Barang::findById($id);
    if ($item) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Detail barang berhasil diambil', 'data' => $item]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Data barang tidak ditemukan']);
    }
    exit;
}

$items = Barang::getAll($jurusanId);
if (!empty($search)) {
    $q = strtolower($search);
    $items = array_values(array_filter($items, function($b) use ($q) {
        return str_contains(strtolower($b['nama_barang'] ?? ''), $q) ||
               str_contains(strtolower($b['merek'] ?? ''), $q);
    }));
}

http_response_code(200);
echo json_encode(['success' => true, 'total' => count($items), 'message' => 'Daftar barang berhasil diambil', 'data' => $items]);
