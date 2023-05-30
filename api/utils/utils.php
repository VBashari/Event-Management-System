<?php

$postPhotosPath = '../../photos/posts';
$servicePhotosPath = '../../photos/services';
$acceptedImageTypes = ['png', 'jpg', 'jpeg'];

/**
 * Check if date matches the MYSQL pattern: if not, adds an error to the class errors
 * 
 * @param string $date
 * @return string
 */
function validateDate($date) {
    if(!$date || !preg_match('\d{4}-\d{2}-\d{2} \d{2}-\d{2}-\d{2}'))
        return 'Invalid date (Accepted values: YYYY-MM-DD HH-MM-SS)';
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

function exitError($code, $message) {
    http_response_code($code);
    echo json_encode(array("error" => $message));
    exit();
}