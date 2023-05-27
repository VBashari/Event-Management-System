<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

require_once __DIR__ . "/utils/Database.php";
require_once __DIR__ . "/utils/config.php";
require_once __DIR__ . "/utils/errors.php";

// convert unhandled errors or exceptions to JSON
set_error_handler("handleError");
set_exception_handler("handleException");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);

$resource = $parts[2] ?? null;
$id = $parts[3] ?? null;

if (count($parts) > 4 || $resource == null) {
    respondError(400, "Invalid request");
    exit();
}

$database = new Database($host, $name, $user, $password);
$method = $_SERVER['REQUEST_METHOD'];

switch ($resource) {
    case "users":
        require_once __DIR__ . "/controllers/UserController.php";
        require_once __DIR__ . "/gateways/UserGateway.php";
        require_once __DIR__ . "/utils/UserType.php";
        
        try {
            $gateway = new UserGateway($database);
        }
        catch (PDOException $e) {
            respondError(500, "Database connection failed");
            exit();
        }

        $controller = new UserController($gateway);

        try {
            $controller->processRequest($method, $id);
        }
        catch (PDOException $e) {
            respondError(500, "Database error");
        }
        break;
    default:
        respondError(404, "Resource not found");
}


