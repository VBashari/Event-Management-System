<?php


function readRequestBody() {
    $body = file_get_contents('php://input');

    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json') {
        return json_decode($body, true);
    }
    else {
        parse_str($body, $data);
        return $data;
    }
}
