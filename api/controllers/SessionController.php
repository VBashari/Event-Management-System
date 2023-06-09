<?php

require_once __DIR__ . '/interfaces/GenericController.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../utils/SessionType.php';
require_once __DIR__ . '/../utils/utils.php';

/**
 * Endpoints:
 *      GET POST     sessions
 *      GET          sessions/{id}
 */

class SessionController implements GenericController {
    private static $errors;
    private static $data;

    private function __construct() {}

    public static function __constructStatic() {
        self::$data = readRequestBody();
    }

    // Auth required: ADMIN
    public static function getAll($limitQueries = null) {
        try {
            http_response_code(200);
            return Session::getAll();
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function get() {
        try {
            $session_id = (int) getURIparam(2);
            $session = Session::get($session_id);
            if ($session === false) {
                exitError(404, "Session with id $session_id does not exist");
            }

            http_response_code(200);
            return $session;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, create new session
     */
    public static function create() {
        self::$errors = [];

        $user_auth = null;
        $session_type = null;
        if (isset(self::$data['email'])) {
            $user_auth = self::$data['email'];
            $session_type = SessionType::EMAIL;
            UserController::validateEmail($user_auth);
        }
        else if (isset(self::$data['username'])) {
            $user_auth = self::$data['username'];
            $session_type = SessionType::USERNAME;
            UserController::validateUsername(user_auth);
        }
        else {
            self::$errors[] = "No email or username provided";
        }
        
        UserController::validatePassword(self::$data['password'] ?? null);

        // AUTH HERE 
        // ...

        // get User info
        $user = null;
        // ...

        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
            $session_id = Session::create([
                'user' => $user
            ]);
            
            http_response_code(201);
            
            return [
                "error" => 0,
                "result" => [
                    "id" => $session_id
                ]
            ];
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }
}

SessionController::__constructStatic();