<?php
// dashboard.php - Entrypoint
require_once __DIR__ . '/app/helpers/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/app/views/dashboard/index.php';
