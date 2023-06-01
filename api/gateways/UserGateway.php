<?php
    class UserGateway {
        public function __construct($database) {
            $this->db = $database->getConnection();
        }

        public function getAll($search = null, $user_type = null, $limit = null, $page = null) {
            $query = "SELECT user_id, user_type, username, email FROM user";
        
            if ($search !== null) {
                $query .= " WHERE username LIKE :search";
            }

            if ($user_type !== null) {
                if ($search === null) {
                    $query .= " WHERE";
                }
                else {
                    $query .= " AND";
                }
                $query .= " user_type = :user_type";
            }

            if ($limit !== null) {
                $query .= " LIMIT :limit";
            }
        
            if ($limit !== null && $page !== null) {
                $offset = ($page - 1) * $limit;
                $query .= " OFFSET :offset";
            }
        
            $stmt = $this->db->prepare($query);
        
            if ($search !== null) {
                $searchTerm = "%{$search}%";
                $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            }

            if ($user_type !== null) {
                $stmt->bindValue(':user_type', $user_type, PDO::PARAM_STR);
            }

            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
        
            if ($limit !== null && $page !== null) {
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
        
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get($userId) {
            $stmt = $this->db->prepare("SELECT user_id, user_type, username, email 
                                        FROM user
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function usernameExists($username) {
            $stmt = $this->db->prepare("SELECT user_id
                                        FROM user
                                        WHERE username = :username");
            
            $stmt->bindParam('username', $username, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        }

        public function emailExists($email) {
            $stmt = $this->db->prepare("SELECT user_id
                                        FROM user
                                        WHERE email = :email");
            
            $stmt->bindParam('email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        }

        public function createUser($user_type, $username, $email, $password) {
            $stmt = $this->db->prepare("INSERT INTO user (user_type, username, email, password)
                                        VALUES (:user_type, :username, :email, :password)");
            
            $stmt->bindParam('user_type', $user_type, PDO::PARAM_STR);
            $stmt->bindParam('username', $username, PDO::PARAM_STR);
            $stmt->bindParam('email', $email, PDO::PARAM_STR);
            $stmt->bindParam('password', $password, PDO::PARAM_STR);
            $stmt->execute();

            return $this->db->lastInsertId();
        }

        public function updateUserType($userId, $user_type) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET user_type = :user_type
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_type', $user_type, PDO::PARAM_STR);
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function updateUsername($userId, $username) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET username = :username
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('username', $username, PDO::PARAM_STR);
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function updateEmail($userId, $email) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET email = :email
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('email', $email, PDO::PARAM_STR);
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function updatePassword($userId, $password) {
            $stmt = $this->db->prepare("UPDATE user
                                        SET password = :password
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('password', $password, PDO::PARAM_STR);
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }

        public function delete($userId) {
            $stmt = $this->db->prepare("DELETE FROM user
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }
    }
