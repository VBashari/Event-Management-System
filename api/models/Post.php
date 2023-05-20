<?php
    require_once __DIR__ . '/Model.php';

    class Post extends Model {
        public function __construct() {
            parent::__construct('post');
        }

        public function getAllPaginated($servicerId, $limit, $offset) {
            $stmt = $this->db->prepare("SELECT * FROM $this->tableName 
                                        WHERE servicer_id = ? LIMIT ? OFFSET ?");

            try {
                $stmt->execute(array($servicerId, $limit, $offset));
            } catch(PDOException $ex) {
                throw $ex;
            }

            return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
        }
    }