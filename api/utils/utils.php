<?php


function readRequestBody() {
    $body = file_get_contents('php://input');

    $headers = getallheaders();
    $contentType = $headers['Content-Type'];

    if ($contentType == 'application/json') {
        return json_decode($body, true);
    }
    else {
        parse_str($body, $data);
        return $data;
    }
}
