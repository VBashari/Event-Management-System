<?php
    class UserGateway {
        public function __construct(private $database) {
            $this->db = $database->getConnection();
        }

        public function getAll() {
            $stmt = $this->db->prepare("SELECT user_id, user_type, username, email 
                                        FROM user");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get($userId) {
            $stmt = $this->db->prepare("SELECT user_id, user_type, username, email 
                                        FROM user
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function usernameExists($username) {
            $stmt = $this->db->prepare("SELECT user_id
                                        FROM user
                                        WHERE username = :username");
            
            $stmt->bindParam('username', $username);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        }

        public function emailExists($email) {
            $stmt = $this->db->prepare("SELECT user_id
                                        FROM user
                                        WHERE email = :email");
            
            $stmt->bindParam('email', $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        }

        public function createUser($user_type, $username, $email, $password) {
            $stmt = $this->db->prepare("INSERT INTO user (user_type, username, email, password)
                                        VALUES (:user_type, :username, :email, :password)");
            
            $stmt->bindParam('user_type', $user_type);
            $stmt->bindParam('username', $username);
            $stmt->bindParam('email', $email);
            $stmt->bindParam('password', $password);
            $stmt->execute();

            return $this->db->lastInsertId();
        }

        public function updateUserType($userId, $user_type) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET user_type = :user_type
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_type', $user_type);
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function updateUsername($userId, $username) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET username = :username
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('username', $username);
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function updateEmail($userId, $email) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET email = :email
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('email', $email);
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function updatePassword($userId, $password) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET password = :password
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('password', $password);
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function delete($userId) {
            $stmt = $this->db->prepare("DELETE FROM user
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_id', $userId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }
    }