<?php

require_once __DIR__ . '/utils/utils.php';
loadEnv(".env");

require_once __DIR__ . '/Router.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');

Router::route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);