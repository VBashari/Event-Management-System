<?php
    require_once __DIR__ . '/Model.php';

    class User extends Model {
        public function __construct() {
            parent::__construct('user');
        }

        public function get($userId) {
            $stmt = $this->db->prepare("SELECT user_id, user_type, username, email 
                                        FROM $this->tableName
                                        WHERE user_id = :user_id");
            
            $stmt->bindParam('user_id', $userId);

            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return json_encode(array_values($stmt->fetch(PDO::FETCH_ASSOC)));
        }

        //?? need to hash password
        public function changePassword($userId, $oldPassword, $newPassword) {}
    }