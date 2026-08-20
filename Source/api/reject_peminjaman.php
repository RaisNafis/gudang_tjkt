<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed. Use POST."]);
    exit();
}

require_once __DIR__ . '/../app/models/Peminjaman.php';

$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);

if (!$data) {
    $data = $_POST;
}

$id = $data['id'] ?? null;

if (empty($id)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Field 'id' wajib diisi dalam request JSON body."]);
    exit();
}

$result = Peminjaman::rejectReturn($id);

if ($result['success']) {
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => $result['message'],
        "id" => $id
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => $result['message']
    ]);
}
