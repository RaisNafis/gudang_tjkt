<?php
/**
 * REST API Endpoint for Approving Return of Borrowed Item
 * URL: POST /api/approve_peminjaman.php
 */
ob_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/Peminjaman.php';
require_once __DIR__ . '/../app/models/LogAktivitas.php';

$rawInput = file_get_contents('php://input');
$jsonBody = !empty($rawInput) ? (json_decode($rawInput, true) ?: []) : [];
$params = array_merge($_POST, $jsonBody);

$id = $params['id'] ?? $params['peminjaman_id'] ?? null;

if (empty($id)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Parameter id transaksi peminjaman wajib diisi!'
    ]);
    exit;
}

$res = Peminjaman::approveReturn($id);

if ($res['success']) {
    LogAktivitas::log('SETUJUI_PENGEMBALIAN', 'Persetujuan pengembalian peminjaman ID: ' . $id);
    http_response_code(200);
} else {
    http_response_code(400);
}

echo json_encode($res);
