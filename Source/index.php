<?php
// index.php - Front Entrypoint
require_once __DIR__ . '/app/helpers/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
