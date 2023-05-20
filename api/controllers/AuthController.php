<?php
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . "/../utils/utils.php";

    class AuthController {
        public function processRequest($method, $id) {
            switch ($method) {
                case 'GET':
                    if ($id == null) {
                        // return all users. auth required: admin
                        $user = new User();
                        // ... auth check
                        echo $user->getAllUsers();
                    }
                    else {
                        // return user with id. auth required: admin or authenticated user with id
                    }
                    break;
                case 'POST':
                    // sign up. auth required: none
                    break;
                case 'PATCH':
                    // update user. auth required: admin or authenticated user with id
                    break;
                case 'DELETE':
                    // delete user. auth required: admin or authenticated user with id
                    break;
                default:
                   exit_400("Invalid request");
                    
            }
        }
    }