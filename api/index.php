<?php

require_once __DIR__ . '/utils/utils.php';
require_once __DIR__ . '/utils/errors.php';

// load sensitive info
loadEnv(".env");

// convert unhandled errors or exceptions to JSON
set_error_handler("handleError");
set_exception_handler("handleException");

require_once __DIR__ . '/Router.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');

Router::route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);