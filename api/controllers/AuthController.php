<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/SessionController.php';

class AuthController {

    // get token from Bearer or Cookie
    private static function getToken() {
        $token = null;
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            // check if bearer and get token
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        } else if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
        }
        return $token;
    }

    public static function getUserType() {
        $token = self::getToken();
        if ($token) {
            $payload = SessionController::verifyJwtToken($token);
            if ($payload) {
                $user_id = $payload['user_id'];
                $user = User::get($user_id);
                if ($user) {
                    return $user['user_type'];
                }
            }
        }
        return null;
    }
}