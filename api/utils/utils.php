<?php

require_once __DIR__ . '/errors.php';

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
}

function loadEnv($file) {
    $env = [];
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (!getenv($key)) {
                putenv("$key=$value");
                $env[$key] = $value;
            }
        }
    }
    return $env;
}

function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function base64UrlDecode($data) {
    $paddedData = str_pad($data, strlen($data) % 4, '=', STR_PAD_RIGHT);
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $paddedData));
}