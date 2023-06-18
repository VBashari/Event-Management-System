<?php

class UserValidator {
    private static $errors;

    /**
     * Check if user type is 'USER', 'VNDR', or 'ORG': if not, adds an error to the class errors
     * 
     * @param string $userType
     */
    public static function validateUserType($userType) {
        if(!$userType)
            self::$errors['user_type'] = 'Required value';
        elseif(!in_array($userType, array(UserType::USER->value, UserType::VENDOR->value, UserType::EVENT_ORGANIZER->value, UserType::ADMIN->value)))
            self::$errors['user_type'] = "Invalid user type (Accepted values: 'USER', 'VNDR', 'ORG', 'ADMN')";
        if($userType == UserType::ADMIN->value)
            AuthController::requireUserType([UserType::ADMIN->value]); // only admins can create other admins
    }

    /**
     * Check if username is 3-40 chars. long: if not, adds an error to the class errors
     * 
     * @param string $username
     */
    public static function validateUsername($username, $isCreate = false) {
        if(!$username)
            self::$errors['username'] = 'Required value';
        elseif(strlen($username) < 3 || strlen($username) > 40)
            self::$errors['username'] = 'Username must be between 3-40 characters';
        elseif(!preg_match('/^[a-zA-Z0-9_-]+$/', $username))
            self::$errors['username'] = 'Username can only contain letters, numbers, underscores and dashes)';
        elseif($isCreate && User::doesUsernameExist($username))
            self::$errors['username'] = 'Username is taken';
    }

    public static function validateFullName($fullName) {
        if(!$fullName)
            self::$errors['full_name'] = 'Required value';
        elseif(strlen($fullName) < 3 || strlen($fullName) > 80)
            self::$errors['full_name'] = 'Name must be between 3-40 characters)';
    }

    /**
     * Check if email is valid: if not, adds an error to the class errors
     * 
     * @param string $email
     */
    public static function validateEmail($email, $isCreate = false) {
        if(!$email)
            self::$errors['email'] = 'Required value';
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
            self::$errors['email'] = 'Invalid email (Accepted format: example@example.com)';
        elseif($isCreate && User::doesEmailExist($email))
            self::$errors['email'] = 'Email is taken';
    }

    /**
    * Check if password is at least 8 characters long, and has at least one uppercase or lowercase letter 
    * and a number: if not, adds an error to the class errors
    * 
    * @param string $password
    */
    public static function validatePassword($password) {
        if(!$password)
            self::$errors['password'] = 'Required value';
        elseif(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password))
            self::$errors['password'] = 'Password must contain 8 or more characters,'
                                        . ' at least one lowercase/uppercase letter, and a number)';
    }

    public static function getErrors() {
        return self::$errors;
    }

    public static function resetErrors() {
        self::$errors = [];
    }
}