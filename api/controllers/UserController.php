<?php
    require_once __DIR__ . "/../utils/utils.php";

    class UserController {
        public function __construct(private $gateway) {
        }

        public function validateUserType($user_type) {
            // validate user type
            if ($user_type != UserType::USER->value &&
            $user_type != UserType::VENDOR->value &&
            $user_type != UserType::EVENT_ORGANIZER->value) {
                respondError(400, "Invalid user type");
                return false;
            }
            return true;
        }
        
        public function validateUsername($username) {
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
                respondError(400, "Username can only contain letters, numbers, underscores and dashes");
                return false;
            }

            if (strlen($username) < 3 || strlen($username) > 40) {
                respondError(400, "Username must be between 3 and 40 characters");
                return false;
            }

            if ($this->gateway->usernameExists($username)) {
                respondError(400, "There is already a user with this username");
                return false;
            }

            return true;
        }

        public function validateEmail($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                respondError(400, "Invalid email");
                return false;
            }

            if (strlen($email) > 50) {
                respondError(400, "Email must be shorter than 50 characters");
                return false;
            }

            if ($this->gateway->emailExists($email)) {
                respondError(400, "There is already a user with this email");
                return false;
            }

            return true;
        }

        public function validatePassword($password) {
            if (strlen($password) < 8) {
                respondError(400, "Password must be longer than 8 characters");
                return false;
            }

            return true;
        }

        public function processRequest($method, $id) {
            if ($id == null) {
                switch ($method) {
                    case 'POST':
                        // sign up
                        // validate input

                        $data = readRequestBody();
                        if ($data == null) {
                            respondError(400, "Invalid request body");
                            return;
                        }

                        $user_type = $data['user_type'] ?? null;
                        $username = $data['username'] ?? null;
                        $email = $data['email'] ?? null;
                        $password = $data['password'] ?? null;
                    
                        if ($username == null || $email == null || $password == null || $user_type == null) {
                            respondError(422, "Missing required fields");
                            return;
                        }

                        if (!$this->validateUserType($user_type)) {
                            return;
                        }

                        if (!$this->validateUsername($username)) {
                            return;
                        }

                        if (!$this->validateEmail($email)) {
                            return;
                        }
                        
                        if (!$this->validatePassword($password)) {
                            return;
                        }

                        // hash password
                        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                        // insert
                        $id = $this->gateway->createUser($user_type, $username, $email, $passwordHash);

                        http_response_code(201);
                        echo json_encode([
                            "error" => 0,
                            "result" => [
                                "id" => $id
                            ]
                        ]);

                        break;

                    case 'GET':
                        // return all users. auth required: admin
                        // ... auth check
                        echo json_encode([
                            "error" => 0,
                            "result" => $this->gateway->getAll()
                        ]);

                        break;
                    default:
                        respondMethodNotAllowed("GET, POST");
                }
            }
            else {
                $user = $this->gateway->get($id);
                if ($user === false) {
                    respondError(404, "User not found");
                    return;
                }

                switch ($method) {
                    case 'GET':
                        // return user with id. auth required: admin or authenticated user with id
                        echo json_encode([
                            "error" => 0,
                            "result" => $user
                        ]);
                        break;
                    case 'PATCH':
                        // update user. auth required: admin or authenticated user with id
                        $data = readRequestBody();
                        if ($data == null) {
                            respondError(400, "Invalid request body");
                            return;
                        }

                        $user_type = $data['user_type'] ?? null;
                        $username = $data['username'] ?? null;
                        $email = $data['email'] ?? null;
                        $password = $data['password'] ?? null;
       
                        if ($user_type != null && $user_type != $user["user_type"]) {
                            if (!$this->validateUserType($user_type)) {
                                return;
                            }

                            $this->gateway->updateUserType($user["user_id"], $user_type);
                        }

                        if ($username != null && $username != $user["username"]) {;
                            if (!$this->validateUsername($username)) {
                                return;
                            }

                            $this->gateway->updateUsername($user["user_id"], $username);
                        }

                        if ($email != null && $email != $user["email"]) {
                            if (!$this->validateEmail($email)) {
                                return;
                            }

                            $this->gateway->updateEmail($user["user_id"], $email);
                        }

                        if ($password != null) {
                            if (!$this->validatePassword($password)) {
                                return;
                            }

                            $this->gateway->updatePassword();
                        }
                        
                        echo json_encode([
                            "error" => 0,
                            "result" => $this->gateway->get($id)
                        ]);

                        break;
                    case 'DELETE':
                        // delete user. auth required: admin or authenticated user with id
                        $res = $this->gateway->delete($id);

                        if (!$res) {
                            respondError(500, "Failed to delete user");
                            return;
                        }
                        
                        echo json_encode([
                            "error" => 0,
                            "result" => "User deleted"
                        ]);

                        break;
                    default:
                        respondMethodNotAllowed("GET, PATCH, DELETE");      
                }
            }
        }
    }