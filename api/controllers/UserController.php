<?php

require_once __DIR__ . '/interfaces/GenericController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/UserType.php';
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
    private static $errors;

    private function __construct() {}

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
            http_response_code(200);
            return User::get((int) getURIparam(2));
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
        self::$errors = [];

        self::validateUserType($_POST['user_type'] ?? null);
        self::validateUsername($_POST['username'] ?? null);
        self::validateEmail($_POST['email'] ?? null);
        self::validatePassword($_POST['password'] ?? null);
        self::validateConfirmPassword($_POST['password'] ?? null, $_POST['confirm_password'] ?? null);
        
        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
            User::$baseModel->insert([
                'user_type' => $_POST['user_type'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password']
            ]);
            http_response_code(201);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate the specified update data and, if no errors occur, perform update
     */
    public static function update() {
        self::$errors = [];
        $data = json_decode(file_get_contents('php://input'), true);

        if(isset($data['username'])) {
            $update['username'] = $data['username'];
            self::validateUsername($update['username']);
        }

        if(isset($data['email'])) {
            $update['email'] = $data['email'];
            self::validateEmail($update['email']);
        }

        if(isset($data['password'])) {
            $update['password'] = $data['password'];
            $update['confirm_password'] = $data['confirm_password'] ?? null;

            self::validatePassword($update['password']);
            self::validateConfirmPassword($update['password'], $update['confirm_password']);
        }

        if(self::$errors)
            exitError(400, self::$errors);
        
        if(isset($update)) {
            try {
                User::$baseModel->update($update, ['user_id' => (int) getURIparam(2)]);
                http_response_code(200);
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }
    }

    public static function delete() {
        try {
            User::$baseModel->delete(['user_id' => (int) getURIparam(2)]);
            http_response_code(204);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    //Validation functions

    /**
     * Check if user type is 'USER', 'VNDR', or 'ORG': if not, adds an error to the class errors
     * 
     * @param string $userType
     */
    private static function validateUserType($userType) {
        if(!$userType)
            self::$errors['user_type'] = 'Required value';
        elseif(!in_array($userType, array(UserType::USER->value, UserType::VENDOR->value, UserType::EVENT_ORGANIZER->value)))
            self::$errors['user_type'] = "Invalid user type (Accepted values: 'USER', 'VNDR', 'ORG')";
    }

    /**
     * Check if username is 3-40 chars. long: if not, adds an error to the class errors
     * 
     * @param string $username
     */
    private static function validateUsername($username) {
        if(!$username)
            self::$errors['username'] = 'Required value';
        elseif(strlen($username) < 3 || strlen($username) > 40)
            self::$errors['username'] = 'Invalid username (Accepted values: 3-40 characters; a-z, A-Z, 0-9, special characters)';
        elseif(User::doesUsernameExist($username))
            self::$errors['username'] = 'Username is taken';
    }

    /**
     * Check if email is valid: if not, adds an error to the class errors
     * 
     * @param string $email
     */
    private static function validateEmail($email) {
        if(!$email)
            self::$errors['email'] = 'Required value';
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
            self::$errors['email'] = 'Invalid email (Accepted format: example@example.com)';
    }

    /**
    * Check if password is at least 8 characters long, and has at least one uppercase or lowercase letter 
    * and a number: if not, adds an error to the class errors
    * 
    * @param string $password
    */
    private static function validatePassword($password) {
        if(!$password)
            self::$errors['password'] = 'Required value';
        elseif(!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[\w]{8,}$/', $password))
            self::$errors['password'] = 'Invalid password (Accepted values: 8 or more characters,'
                                        . ' at least one lowercase/uppercase letter, and a number)';
    }

    /**
     * Check if confirm-password is inputted and matches with password: if not, adds an error to the class errors
     * 
     * @param string $password
     * @param string $confirmPassword
     */
    private static function validateConfirmPassword($password, $confirmPassword) {
        if(!$confirmPassword)
            self::$errors['confirm_password'] = 'Required value';
        elseif(strcmp($password, $confirmPassword) != 0)
            self::$errors['confirm_password'] = 'Password & confirm-password do not match';
    }
}