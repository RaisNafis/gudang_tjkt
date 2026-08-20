<?php
// swagger.php - Direct Route to Official Default Swagger UI
require_once __DIR__ . '/app/helpers/auth.php';
requireLogin();

$user = currentUser();
if (empty($user['peran']) || $user['peran'] !== 'admin_sekolah') {
    http_response_code(403);
    die('Akses Ditolak: Dokumentasi API Swagger hanya dapat diakses oleh Admin Sekolah.');
}

require_once __DIR__ . '/swagger_ui.php';
