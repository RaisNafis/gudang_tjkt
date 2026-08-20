<?php
/**
 * REST API Endpoint for Pengembalian Barang Inventaris
 * URL: POST /api/return_peminjaman.php
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
$buktiFoto = $params['bukti_foto'] ?? null;

if (empty($id)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Parameter id transaksi peminjaman wajib diisi!'
    ]);
    exit;
}

// Process Upload Bukti Foto jika multipart file
if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/bukti/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = 'bukti_' . time() . '_' . bin2hex(random_bytes(4)) . '.dat';
    $destination = $uploadDir . $filename;

    $tmpPath = $_FILES['bukti_foto']['tmp_path'] ?? $_FILES['bukti_foto']['tmp_name'];
    $imgData = file_get_contents($tmpPath);
    $compressedData = gzdeflate($imgData, 9);
    file_put_contents($destination, $compressedData);
    $buktiFoto = 'uploads/bukti/' . $filename;
} elseif (!empty($buktiFoto) && str_starts_with($buktiFoto, 'data:image')) {
    // Process Base64 image string
    $uploadDir = __DIR__ . '/../uploads/bukti/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = 'bukti_' . time() . '_' . bin2hex(random_bytes(4)) . '.dat';
    $destination = $uploadDir . $filename;

    $parts = explode(',', $buktiFoto);
    $imgData = base64_decode(end($parts));
    if ($imgData) {
        $compressedData = gzdeflate($imgData, 9);
        file_put_contents($destination, $compressedData);
        $buktiFoto = 'uploads/bukti/' . $filename;
    }
}

$res = Peminjaman::returnItem($id, $buktiFoto);

if ($res['success']) {
    LogAktivitas::log('PENGEMBALIAN_ALAT', 'Pengembalian alat peminjaman ID: ' . $id);
    http_response_code(200);
} else {
    http_response_code(400);
}

echo json_encode($res);
