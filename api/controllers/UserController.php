<?php
    require_once __DIR__ . "/../utils/utils.php";
    require_once __DIR__ . "/../validators/UserValidator.php";

    class UserController {
        public function __construct(private $gateway) {
        }

        public function validateUser($user_type, $username, $email, $password) {
            $errors = validateUserData($user_type, $username, $email, $password);
        
            if ($username !== null) {
                if ($this->gateway->usernameExists($username)) {
                    $errors[] = "There is already a user with this username";
                }
            }

            if ($email !== null) {
                if ($this->gateway->emailExists($email)) {
                    $errors[] = "There is already a user with this email";
                }
            }

            return $errors;
        }

        public function processRequest($method, $id) {
            if ($id == null) {
                switch ($method) {
                    case 'POST':
                        // sign up
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
                            respondError(400, "Missing required fields");
                            return;
                        }

                        $errors = $this->validateUser($user_type, $username, $email, $password);
                        if (!empty($errors)) {
                            respondError(400, $errors);
                            return;
                        }
                        
                        // hash password
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

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
                            "result" => $this->gateway->getAll($_GET["search"] ?? null, $_GET["type"] ?? null, $_GET["limit"] ?? null, $_GET["page"] ?? null)
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
       
                        if ($user_type == $user["user_type"])
                            $user_type = null;
                        if ($username == $user["username"])
                            $username = null;
                        if ($email == $user["email"])
                            $email = null;

                        $errors = $this->validateUser($user_type, $username, $email, $password);
                        if (!empty($errors)) {
                            respondError(400, $errors);
                            return;
                        }
                        
                        if ($user_type !== null)
                            $this->gateway->updateUserType($user["user_id"], $user_type);
                        if ($username !== null)
                            $this->gateway->updateUsername($user["user_id"], $username);
                        if ($email !== null)
                            $this->gateway->updateEmail($user["user_id"], $email);

                        if ($password !== null) {
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                            $this->gateway->updatePassword($user["user_id"], $passwordHash);
                        }

                        $user = $this->gateway->get($id);

                        echo json_encode([
                            "error" => 0,
                            "result" => $user
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