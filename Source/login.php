<?php
// login.php - Entrypoint
date_default_timezone_set('Asia/Jakarta');
require_once __DIR__ . '/app/controllers/AuthController.php';

$authController = new AuthController();
$authController->login();
