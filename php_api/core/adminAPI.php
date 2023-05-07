<?php

require_once __DIR__ . '/../includes/config.php';

class AdminAPI {
    private $db;

    public function __construct() {
        $this->db = new Connector;
    }

    public function getAllPosts($limit, $offset) {
        $stmt = $this->db->prepare('SELECT s.service_id, servicer.user_id, servicer.username, s.title, s.description, s.avg_price
                                    FROM service s
                                    INNER JOIN user servicer ON servicer.user_id = s.servicer_id
                                    LIMIT ? OFFSET ?');
        
        $stmt->execute(array($limit, $offset));
        return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }

    public function deletePost($postId) {
        $stmt = $this->db->prepare('DELETE from service WHERE service_id = :post_id');
        $stmt->bindParam('post_id', $postId);

        $stmt->execute();
    }

    public function deleteUser($userId) {
        $stmt = $this->db->prepare('DELETE from user WHERE user_id = :user_id');
        $stmt->bindParam('user_id', $userId);

        $stmt->execute();
    }
}

// $a = new AdminAPI();
// echo $a->getAllPosts(10, 0);

?>