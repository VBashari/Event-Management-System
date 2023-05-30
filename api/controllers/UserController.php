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
        self::validateUserType($_POST['user_type']);
        self::validateUsername($_POST['username']);
        self::validateEmail($_POST['email']);
        self::validatePassword($_POST['password']);
        self::validateConfirmPassword($_POST['password'], $_POST['confirm_password']);
        
        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
            User::getBase()->insert([
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
        if($_REQUEST['username']) {
            $update['username'] = $_REQUEST['username'];
            self::validateUsername($update['username']);
        }

        if($_REQUEST['email']) {
            $update['email'] = $_REQUEST['email'];
            self::validateEmail($update['email']);
        }

        if($_REQUEST['password']) {
            $update['password'] = $_REQUEST['password'];
            $update['confirm_password'] = $_REQUEST['confirm_password'];

            self::validatePassword($update['password']);
            self::validateConfirmPassword($update['confirm_password']);
        }

        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
            User::getBase()->update($update, ['user_id' => (int) getURIparam(2)]);
            http_response_code(200);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function delete() {
        try {
            User::getBase()->delete(['user_id' => (int) getURIparam(2)]);
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
        if($input[$this->userTypeField] || in_array($input[$userType], array(UserType::USER, UserType::VENDOR, UserType::EVENT_ORGANIZER)))
            $this->errors[$this->userTypeField] = "Invalid user type (Accepted values: 'USER', 'VNDR', 'ORG')";
    }

    /**
     * Check if username is 3-40 chars. long: if not, adds an error to the class errors
     * 
     * @param string $username
     */
    private static function validateUsername($username) {
        if(!$username || strlen($username) < 3 || strlen($username) > 40)
            self::$errors['username'] = 'Invalid username (Accepted values: 3-40 characters; a-z, A-Z, 0-9, special characters)';
        
        if($this->user->doesUsernameExist($username))
            self::$errors['username'] = 'Username is taken';
    }

    /**
     * Check if email is valid: if not, adds an error to the class errors
     * 
     * @param string $email
     */
    private static function validateEmail($email) {
        if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
            self::$errors['email'] = 'Invalid email (Accepted format: example@example.com)';
    }

    /**
    * Check if password is at least 8 characters long, and has at least one uppercase or lowercase letter 
    * and a number: if not, adds an error to the class errors
    * 
    * @param string $password
    */
    private static function validatePassword($password) {
        if(!$password || !preg_match('^(?=.*[a-zA-Z](?=.*\d)[\w]{8,}', $password))
            $this->errors[$this->passwordField] = 'Invalid password (Accepted values: 8 or more characters,'
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
            $this->errors['confirm_password'] = 'Confirm-password is required';

        if(strcmp($password, $confirmPassword) != 0)
            $this->errors['confirm_password'] = 'Password & confirm-password do not match';
    }
}