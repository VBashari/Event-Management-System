<?php

require_once __DIR__ . '/interfaces/GenericController.php';
// require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../utils/SessionType.php';
require_once __DIR__ . '/../utils/utils.php';
require_once __DIR__ . '/../validators/UserValidator.php';

/**
 * Endpoints:
 *      POST     sessions
 */

class SessionController  {
    private static $errors;
    private static $data;

    private function __construct() {}

    public static function __constructStatic() {
        self::$data = readRequestBody();
    }

    private static function generateJwtToken($payload) {
        $header = base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64UrlEncode(json_encode($payload));
        $signature = base64UrlEncode(hash_hmac('sha256', "$header.$payload", getenv('JWT_SECRET_KEY'), true));

        return "$header.$payload.$signature";
    }

    public static function verifyJwtToken($token) {
        list($header, $payload, $signature) = explode('.', $token);
        
        $header = json_decode(base64UrlDecode($header), true);
        $payload = json_decode(base64UrlDecode($payload), true);

        if (isset($payload['iss']) && $payload['iss'] != $_SERVER['HTTP_HOST']) {
            return null;
        }

        $currentTimestamp = time();
        if (isset($payload['nbf']) && $payload['nbf'] > $currentTimestamp) {
            return null;
        }

        if (isset($payload['exp']) && $payload['exp'] < $currentTimestamp) {
            return null;
        }
    
        $user_id = $payload['user_id'] ?? null;
        if ($user_id === null) {
            return null;
        }

        $user = User::get($user_id);
        if ($user === false) {
            return null;
        }

        $expectedSignature = base64UrlEncode(hash_hmac('sha256', base64UrlEncode(json_encode($header)) . "." . base64UrlEncode(json_encode($payload)), getenv('JWT_SECRET_KEY'), true));
        if ($signature === $expectedSignature) {
            return $payload;
        } else {
            return null;
        }
    }

    /**
     * Validate input data and, if no errors occur, create new session
     */
    public static function create() {
        UserValidator::resetErrors();

        $user_auth = null;
        $session_type = null;
        if (isset(self::$data['email'])) {
            $user_auth = self::$data['email'];
            $session_type = SessionType::EMAIL;
            UserValidator::validateEmail($user_auth);
        }
        else if (isset(self::$data['username'])) {
            $user_auth = self::$data['username'];
            $session_type = SessionType::USERNAME;
            UserValidator::validateUsername($user_auth);
        }
        else {
            exitError(400, "No email or username provided");
        }
        
        UserValidator::validatePassword(self::$data['password'] ?? null);
        $errors = UserValidator::getErrors();
        if ($errors)
            exitError(400, $errors);
        
        $password = null;

        // get user with email or username
        if ($session_type == SessionType::EMAIL) {
            $user = User::getByEmail($user_auth, null);
            if ($user === false) {
                exitError(401, "Invalid credentials");
            }
        }
        else {
            $user = User::getByUsername($user_auth, null);
            if ($user === false) {
                exitError(401, "Invalid credentials");
            }
        }

        // check password
        if (!password_verify(self::$data['password'], $user['password'])) {
            exitError(401, "Invalid credentials");
        }
        
        try {
            $date = new DateTimeImmutable();
            $expire_at = $date->modify('+48 hours')->getTimestamp();
            $domainName = $_SERVER['HTTP_HOST'];

            $session_id = self::generateJwtToken([
                'iat' => $date->getTimestamp(),
                'iss' => $domainName,
                'nbf' => $date->getTimestamp(),
                'exp' => $expire_at,
                'user_id' => $user['user_id']
            ]);
            
            http_response_code(201);
            
            return [
                "error" => 0,
                "result" => [
                    "token" => $session_id,
                    "expiration" => $expire_at
                ]
            ];
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }
}

SessionController::__constructStatic();