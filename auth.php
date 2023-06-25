<?php
require_once __DIR__ . '/api/utils/utils.php';
require_once __DIR__ . '/api/utils/errors.php';

// load sensitive info
loadEnv(__DIR__ . "/.env");

require_once __DIR__ . '/api/controllers/AuthController.php';

$user = AuthController::getUser();

return $user;
?>