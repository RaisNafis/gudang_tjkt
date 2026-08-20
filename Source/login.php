<?php
// login.php - Entrypoint
require_once __DIR__ . '/app/controllers/AuthController.php';

$authController = new AuthController();
$authController->login();
