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
        } else if (isset($_COOKIE['session'])) {
            $token = $_COOKIE['session'];
        }
        return $token;
    }

    public static function getUser() {
        $token = self::getToken();
        if ($token) {
            $payload = SessionController::verifyJwtToken($token);
            if ($payload) {
                $user_id = (int)$payload['user_id'];
                $user = User::get($user_id);
                if ($user) {
                    return $user;
                }
            }
        }
        return null;
    }

    public static function getUserType() {
        $user = self::getUser();
        if ($user) {
            return $user['user_type'];
        }
        return null;
    }

    public static function requireUserType($allowed = []) {
        if (in_array(null, $allowed)) {
            return;
        }

        $logged_in = self::getUserType();
        if ($logged_in == UserType::ADMIN->value) {
            return;
        }
        
        if (!$logged_in) {
            exitError(401, "Unauthorized");
        }
        
        if (!in_array($logged_in, $allowed)) {
            exitError(403, "Forbidden");
        }
    }

    public static function requireUser($allowed) {
        $user = self::getUser();
        $user_type = $user['user_type'] ?? null;
        $user_id = $user['user_id'] ?? null;

        if ($user_type == UserType::ADMIN->value) {
            return;
        }

        if (!$user) {
            exitError(401, "Unauthorized");
        }

        if ($user_id != $allowed) {
            exitError(403, "Forbidden");
        }
    }
}