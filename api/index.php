<?php

require_once __DIR__ . "/utils/utils.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);

$resource = $parts[2] ?? null;
$id = $parts[3] ?? null;

if (count($parts) > 4 || $resource == null) {
    exitError(400, "Invalid request");
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($resource) {
    case "users":
        require_once __DIR__ . "/controllers/UserController.php";
        $controller = new UserController();
        $controller->processRequest($method, $id);
        break;
    default:
        exitError(404, "Resource not found");
}


