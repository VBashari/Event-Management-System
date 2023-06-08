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
<<<<<<< HEAD

    private function __construct() {}

=======
    private static $data;

    private function __construct() {}

    public static function __constructStatic() {
        self::$data = readRequestBody();
    }

>>>>>>> api_rewrite
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
<<<<<<< HEAD
            http_response_code(200);
            return User::get((int) getURIparam(2));
=======
            $user_id = (int) getURIparam(2);
            $user = User::get($user_id);
            if ($user === false) {
                exitError(404, "User with id $user_id does not exist");
            }
            return $user;
>>>>>>> api_rewrite
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
<<<<<<< HEAD
        self::validateUserType($_POST['user_type']);
        self::validateUsername($_POST['username']);
        self::validateEmail($_POST['email']);
        self::validatePassword($_POST['password']);
        self::validateConfirmPassword($_POST['password'], $_POST['confirm_password']);
        
=======
        self::$errors = [];

        //echo "create " . self::$data['user_type'];
        
        self::validateUserType(self::$data['user_type'] ?? null);
        self::validateUsername(self::$data['username'] ?? null);
        self::validateFullName(self::$data['full_name'] ?? null);
        self::validateEmail(self::$data['email'] ?? null);
        self::validatePassword(self::$data['password'] ?? null);
   
>>>>>>> api_rewrite
        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
<<<<<<< HEAD
            User::getBase()->insert([
                'user_type' => $_POST['user_type'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password']
            ]);
            http_response_code(201);
=======
            $id = User::$baseModel->insert([
                'user_type' => self::$data['user_type'],
                'username' => self::$data['username'],
                'email' => self::$data['email'],
                'password' => self::$data['password']
            ]);
            http_response_code(201);
            
            return [
                "error" => 0,
                "result" => [
                    "id" => $id
                ]
            ];
>>>>>>> api_rewrite
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate the specified update data and, if no errors occur, perform update
     */
    public static function update() {
<<<<<<< HEAD
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
=======
        self::$errors = [];

        $user_id = (int) getURIparam(2);
        $user = User::get($user_id);
        if ($user === false) {
            exitError(404, "User with id $user_id does not exist");
        }

        if(isset(self::$data['username'])) {
            $update['username'] = self::$data['username'];
            self::validateUsername($update['username']);
        }

        if(isset(self::$data['email'])) {
            $update['email'] = self::$data['email'];
            self::validateEmail($update['email']);
        }

        if(isset(self::$data['password'])) {
            $update['password'] = self::$data['password'];
            self::validatePassword($update['password']);
        }

        if(isset(self::$data['full_name'])) {
            $update['full_name'] = self::$data['full_name'];
            self::validateFullName($update['full_name']);
>>>>>>> api_rewrite
        }

        if(self::$errors)
            exitError(400, self::$errors);
        
<<<<<<< HEAD
        try {
            User::getBase()->update($update, ['user_id' => (int) getURIparam(2)]);
            http_response_code(200);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
=======
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
>>>>>>> api_rewrite
        }
    }

    public static function delete() {
        try {
<<<<<<< HEAD
            User::getBase()->delete(['user_id' => (int) getURIparam(2)]);
=======
            $user_id = (int) getURIparam(2);
            $user = User::get($user_id);
            if ($user === false) {
                exitError(404, "User with id $user_id does not exist");
            }
            
            User::$baseModel->delete(['user_id' => (int) getURIparam(2)]);
>>>>>>> api_rewrite
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
<<<<<<< HEAD
        if($input[$this->userTypeField] || in_array($input[$userType], array(UserType::USER, UserType::VENDOR, UserType::EVENT_ORGANIZER)))
            $this->errors[$this->userTypeField] = "Invalid user type (Accepted values: 'USER', 'VNDR', 'ORG')";
=======
        if(!$userType)
            self::$errors['user_type'] = 'Required value';
        elseif(!in_array($userType, array(UserType::USER->value, UserType::VENDOR->value, UserType::EVENT_ORGANIZER->value)))
            self::$errors['user_type'] = "Invalid user type (Accepted values: 'USER', 'VNDR', 'ORG')";
>>>>>>> api_rewrite
    }

    /**
     * Check if username is 3-40 chars. long: if not, adds an error to the class errors
     * 
     * @param string $username
     */
    private static function validateUsername($username) {
<<<<<<< HEAD
        if(!$username || strlen($username) < 3 || strlen($username) > 40)
            self::$errors['username'] = 'Invalid username (Accepted values: 3-40 characters; a-z, A-Z, 0-9, special characters)';
        
        if($this->user->doesUsernameExist($username))
            self::$errors['username'] = 'Username is taken';
    }

=======
        if(!$username)
            self::$errors['username'] = 'Required value';
        elseif(strlen($username) < 3 || strlen($username) > 40)
            self::$errors['username'] = 'Invalid username (Accepted values: 3-40 characters; a-z, A-Z, 0-9, special characters)';
        elseif(User::doesUsernameExist($username))
            self::$errors['username'] = 'Username is taken';
    }

    private static function validateFullName($fullName) {
        if(!$fullName)
            self::$errors['full_name'] = 'Required value';
        elseif(strlen($fullName) < 3 || strlen($fullName) > 80)
            self::$errors['full_name'] = 'Invalid name (3-40 characters long)';
    }

>>>>>>> api_rewrite
    /**
     * Check if email is valid: if not, adds an error to the class errors
     * 
     * @param string $email
     */
    private static function validateEmail($email) {
<<<<<<< HEAD
        if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
            self::$errors['email'] = 'Invalid email (Accepted format: example@example.com)';
=======
        if(!$email)
            self::$errors['email'] = 'Required value';
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
            self::$errors['email'] = 'Invalid email (Accepted format: example@example.com)';
        elseif(User::doesEmailExist($email))
            self::$errors['email'] = 'Email is taken';
>>>>>>> api_rewrite
    }

    /**
    * Check if password is at least 8 characters long, and has at least one uppercase or lowercase letter 
    * and a number: if not, adds an error to the class errors
    * 
    * @param string $password
    */
    private static function validatePassword($password) {
<<<<<<< HEAD
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
=======
        if(!$password)
            self::$errors['password'] = 'Required value';
        elseif(!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[\w]{8,}$/', $password))
            self::$errors['password'] = 'Invalid password (Accepted values: 8 or more characters,'
                                        . ' at least one lowercase/uppercase letter, and a number)';
    }
}

UserController::__constructStatic();
>>>>>>> api_rewrite
