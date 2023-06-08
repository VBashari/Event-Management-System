<?php

/**
 * Check if date matches the MYSQL pattern: if not, adds an error to the class errors
 * 
 * @param string $date
 * @return string
 */
function validateDate($date) {
    if(!$date)
        return 'Required value';

    if(!preg_match_all("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $date))
        return 'Invalid date (Accepted values: YYYY-MM-DD HH:MM:SS)';
    
    return null;
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
    echo json_encode(is_array($message) ? $message : array("error" => $message));
    exit();
}