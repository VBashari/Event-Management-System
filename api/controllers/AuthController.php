<?php
    require_once __DIR__ . '/../models/User';

    class AuthController {
        private $output = array('STATUS' => null, 'ERRORS' => [], 'BODY' => []);

        public function register() {
            switch($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $output['STATUS'] = 405;
                    return $output;
                case 'POST':
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $userType = $_POST['user_type'];
                    $password = $_POST['password'];
                    $confirmPassword = $_POST['confirm_password'];

                    if(!$username || !$email || !$userType || !$password || !$confirmPassword) {
                        if(!$username)
                            $output['ERRORS']['username'] = 'Username is required';
                        
                        if(!$email)
                            $output['ERRORS']['email'] = 'Email is required';
                        
                        if(!$userType)
                            $output['ERRORS']['user_type'] = 'User type is required';

                        if(!$password)
                            $output['ERRORS']['password'] = 'Password is required';
                        
                        if(!$confirmPassword)
                            $output['ERRORS']['confirm_password'] = 'Confirm password is required';
                    }

                    if(strlen($username) < 3 || strlen($username) > 40)
                        $output['ERRORS']['username'] = 'Invalid username format (3-40 characters)';
                    
                    if(preg_match('[!/\@#$%^*()]', $username))
                        $output['ERRORS']['username'] = 'Invalid username format (a-z, A-Z, 0-9,)';

                    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                        $output['ERRORS']['email'] = 'Invalid email format';

            }
        }
    }