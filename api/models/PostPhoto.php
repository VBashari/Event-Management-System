<?php
    require_once __DIR__ . '/Model.php';

    class PostPhoto extends Model {
        public function __construct() {
            parent::__construct('post_photo');
        }

        public function getAllBy($postId) {
            $stmt = $this->db->prepare("SELECT * FROM $this->tableName WHERE post_id = :postId");
            $stmt->bindParam('postId', $postId);

            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
        }
    }