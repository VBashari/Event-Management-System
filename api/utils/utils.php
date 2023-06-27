<?php

$postPhotosPath = '../photos/posts';
$servicePhotosPath = '../photos/services';
$acceptedImageTypes = ['png', 'jpg', 'jpeg'];

require_once __DIR__ . '/errors.php';

/**
 * Check if date matches the MYSQL pattern: if not, adds an error to the class errors
 * 
 * @param string $date
 * @return string
 */
function validateDate($date) {
    if (!$date)
        return 'Required value';

    if(!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $date))
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

    static $data = null;
    if ($data !== null) {
        return $data;
    }

    if (!function_exists('getallheaders')) {
        function getallheaders() {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
    }

    $headers = getallheaders();
    $contentType = $headers['Content-Type'] ?? "";

    if (strpos($contentType, 'application/json') !== false) {
        $data = json_decode($body, true);
        $files = getPhotos($data);
        $data['files'] = $files;
        return $data;
    }
    elseif (strpos($contentType, 'multipart/form-data') !== false) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            exitError(405, "Multi-part form data is only allowed for POST requests");
        }

        $data = [];
        
        foreach ($_POST as $name => $value) {
            $data[$name] = $value;
        }

        foreach ($_FILES as $name => $file) {
            $data['files'][$name] = $file;
        }
        
        return $data;
    } 
    elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
        parse_str($body, $data);
        $files = getPhotos($data);
        $data['files'] = $files;
        return $data;
    }
}

// construct a $_FILES-like array from the base64-encoded file data when content-type is not multipart/form-data
function getPhotos($data) {
    static $files = null;
    if ($files !== null) {
        return $files;
    }
    
    $photos = $data['photos'] ?? null;
    $files['photos'] = [];

    if (is_array($photos)) {
        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            
            $decodedData = base64_decode($photo['data']);
            $tempFilePath = tempnam(sys_get_temp_dir(), 'EM_photo_');
            if (!$tempFilePath) {
                exitError(500, "Failed to create temporary file");
            }

            file_put_contents($tempFilePath, $decodedData);
            $fileExtension = pathinfo($photo['filename'], PATHINFO_EXTENSION);
            $isPhoto = getimagesize($tempFilePath) !== false && in_array($fileExtension, ['jpg', 'jpeg', 'png']);
            if ($isPhoto) {
                $files['photos']['name'][$i] = $photo['filename'];
                $files['photos']['full_path'][$i] = $photo['filename'];
                $files['photos']['type'][$i] = mime_content_type($tempFilePath);
                $files['photos']['tmp_name'][$i] = $tempFilePath;
                $files['photos']['error'][$i] = UPLOAD_ERR_OK;
                $files['photos']['size'][$i] = strlen($decodedData);
            }
            else {
                unlink($tempFilePath);
            }
        }
    }

    return $files;
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