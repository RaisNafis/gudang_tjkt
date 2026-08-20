<?php
/**
 * REST API Endpoint for User Authentication (Login)
 * URL: POST /api/login.php
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
require_once __DIR__ . '/../app/controllers/AuthController.php';

$rawInput = file_get_contents('php://input');
$jsonBody = !empty($rawInput) ? (json_decode($rawInput, true) ?: []) : [];
$params = array_merge($_POST, $jsonBody);

$username = trim($params['nama_pengguna'] ?? $params['username'] ?? '');
$password = $params['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parameter nama_pengguna dan password wajib diisi!']);
    exit;
}

$auth = new AuthController();
$result = $auth->apiLogin($username, $password);

if ($result['success']) {
    http_response_code(200);
} else {
    http_response_code(401);
}

echo json_encode($result);
