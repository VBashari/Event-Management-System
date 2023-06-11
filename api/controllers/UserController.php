<?php

require_once __DIR__ . '/interfaces/GenericController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../validators/UserValidator.php';
require_once __DIR__ . '/../utils/utils.php';

//TODO add auth check

/**
 * Endpoints:
 *      GET POST        users
 *      GET POST        users?limit={}&offset={}
 *      GET PATCH DEL   users/{id}
 *      GET             users?type={type}
 *      GET             users?type={type}&limit={}&offset={}
 */

class UserController implements GenericController {
    private static $data;

    private function __construct() {}

    public static function __constructStatic() {
        self::$data = readRequestBody();
    }

    public static function getAll($limitQueries = null) {
        try {
            http_response_code(200);
            return User::getAll($limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function getAllByType($queries) {
        try {
            http_response_code(200);
            return $queries['type'] == 'user' ? User::getAllUsers($queries['limit'] ?? null, $queries['offset'] ?? null) : 
                                                User::getAllServicers($queries['limit'] ?? null, $queries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function get() {
        try {
            $user_id = (int) getURIparam(2);
            $user = User::get($user_id);
            if ($user === false) {
                exitError(404, "User with id $user_id does not exist");
            }

            http_response_code(200);
            return $user;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
        UserValidator::resetErrors();

        //echo "create " . self::$data['user_type'];
        
        UserValidator::validateUserType(self::$data['user_type'] ?? null);
        UserValidator::validateUsername(self::$data['username'] ?? null, true);
        UserValidator::validateFullName(self::$data['full_name'] ?? null);
        UserValidator::validateEmail(self::$data['email'] ?? null, true);
        UserValidator::validatePassword(self::$data['password'] ?? null);
        $errors = UserValidator::getErrors();

        if($errors) {
            exitError(400, $errors);
        }
        
        try {
            $passwordHash = password_hash(self::$data['password'], PASSWORD_DEFAULT);
            $user_id = User::$baseModel->insert([
                'user_type' => self::$data['user_type'],
                'username' => self::$data['username'],
                'email' => self::$data['email'],
                'password' => $passwordHash,
            ]);
            
            http_response_code(201);
            
            return [
                "error" => 0,
                "result" => [
                    "id" => $user_id
                ]
            ];
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate the specified update data and, if no errors occur, perform update
     */
    public static function update() {
        UserValidator::resetErrors();

        $user_id = (int) getURIparam(2);
        $user = User::get($user_id);
        if ($user === false) {
            exitError(404, "User with id $user_id does not exist");
        }

        if(isset(self::$data['username'])) {
            $update['username'] = self::$data['username'];
            UserValidator::validateUsername($update['username']);
        }

        if(isset(self::$data['email'])) {
            $update['email'] = self::$data['email'];
            UserValidator::validateEmail($update['email']);
        }

        if(isset(self::$data['password'])) {
            $passwordHash = password_hash(self::$data['password'], PASSWORD_DEFAULT);
            $update['password'] = $passwordHash;
            UserValidator::validatePassword($update['password']);
        }

        if(isset(self::$data['full_name'])) {
            $update['full_name'] = self::$data['full_name'];
            UserValidator::validateFullName($update['full_name']);
        }

        $errors = UserValidator::getErrors();
        if($errors)
            exitError(400, $errors);
        
        if(isset($update)) {
            try {
                User::$baseModel->update($update, ['user_id' => $user_id]);
                $user = User::get($user_id);
                return [
                    "error" => 0,
                    "result" => $user
                ];
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }
    }

    public static function delete() {
        try {
            $user_id = (int) getURIparam(2);
            $user = User::get($user_id);
            if ($user === false) {
                exitError(404, "User with id $user_id does not exist");
            }
            
            User::$baseModel->delete(['user_id' => (int) getURIparam(2)]);
            http_response_code(204);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }
}

UserController::__constructStatic();