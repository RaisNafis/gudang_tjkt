<?php
// update_profile.php
require_once __DIR__ . '/app/controllers/ProfileController.php';

$controller = new ProfileController();
$controller->update();
