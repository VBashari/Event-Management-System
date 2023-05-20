<?php

function exitError($code, $message) {
    http_response_code($code);
    echo json_encode(array("error" => $message));
    exit();
}