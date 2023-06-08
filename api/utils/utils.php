<?php

<<<<<<< HEAD
$postPhotosPath = '../photos/posts';
$servicePhotosPath = '../photos/services';
$acceptedImageTypes = ['png', 'jpg', 'jpeg'];
=======
require_once __DIR__ . '/errors.php';
>>>>>>> api_rewrite

/**
 * Check if date matches the MYSQL pattern: if not, adds an error to the class errors
 * 
 * @param string $date
 * @return string
 */
function validateDate($date) {
<<<<<<< HEAD
    if(!$date || !preg_match('\d{4}-\d{2}-\d{2} \d{2}-\d{2}-\d{2}'))
        return 'Invalid date (Accepted values: YYYY-MM-DD HH-MM-SS)';
=======
    if(!$date)
        return 'Required value';

    if(!preg_match_all("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $date))
        return 'Invalid date (Accepted values: YYYY-MM-DD HH:MM:SS)';
    
    return null;
>>>>>>> api_rewrite
}

/**
 * Get specified URI section
 */
function getURIparam($paramIndex) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pathParts = explode('/', $path);
    array_shift($pathParts);

    return $pathParts[$paramIndex];
}

<<<<<<< HEAD
function exitError($code, $message) {
    http_response_code($code);
    echo json_encode(array("error" => $message));
    exit();
=======
function readRequestBody() {
    $body = file_get_contents('php://input');

    $headers = getallheaders();
    $contentType = $headers['Content-Type'] ?? null;

    if ($contentType == 'application/json') {
        return json_decode($body, true);
    }
    else {
        parse_str($body, $data);
        return $data;
    }
>>>>>>> api_rewrite
}