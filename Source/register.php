<?php
// register.php - Entrypoint (Registration Disabled - Redirect to Login)
header('Location: login.php');
exit;

/*
require_once __DIR__ . '/app/controllers/AuthController.php';

$authController = new AuthController();
$authController->register();
*/
