<?php

function handleError($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

function handleException($exception) {
    http_response_code(500);

    echo json_encode([
        "error" => 1,
        "code" => $exception->getCode(),
        "message" => $exception->getMessage(),
        "file" => $exception->getFile(),
        "line" => $exception->getLine()
    ]);
}

function respondError($code, $message) {
    http_response_code($code);
    echo json_encode([
        "error" => $code,
        "message" => $message
    ]);
}

function respondMethodNotAllowed($allowed) {
    http_response_code(405);
    header("Allow: $allowed");
}