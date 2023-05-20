<?php

require_once __DIR__ . '/../includes/config.php';

class UserAPI {
    private $db;
    private $userId;

    public function __construct($userId) {
        $this->db = new Connector;
        $this->userId = $userId;
    }

    

    public function getRequests($limit, $offset) {
        $stmt = $this->db->prepare('SELECT req.*, user.username AS servicer_username
                                    FROM request req
                                    INNER JOIN user ON user.user_id = req.servicer_id
                                    WHERE req.requester_id = ?
                                    LIMIT ? OFFSET ?');

        $stmt->execute($this->userId, $limit, $offset);
        return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }

    public static function createRequest($servicerId, $title, $scheduledDate, $description = NULL) {
        try {
            $stmt = $this->db->prepare('INSERT INTO request (requester_id, servicer_id, title, description, scheduled_date) 
                                        VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array($this->userId, $servicerId, $title, $description, $scheduledDate));
        } catch(PDOException $ex) {
            return $ex->getMessage();
        }
    }
    
    public static function deleteRequest($requestId) {
        try {
            $stmt = $this->db->prepare('DELETE FROM request WHERE request_id = :request_id');
            $stmt->bindParam('request_id', $requestId);
            $stmt->execute();
        } catch(PDOException $ex) {
            return $ex->getMessage();
        }
    }

    public function getServicePosts($servicerType, $searchQuery, $limit, $offset) {        
        $query =    'SELECT s.*, u.username
                    FROM service s
                    INNER JOIN user u
                    ON u.user_id = s.servicer_id';

        //Adding filters
        if($servicerType || $searchQuery) {
            $query .= ' WHERE ';    
            $servicerFilter = 'u.user_type = :servicer_type';
            $searchFilter = 's.service_id IN (SELECT service_id FROM service_tag WHERE tag LIKE :search_query)';
            
            if($servicerType && $searchQuery)
                $query .= $servicerFilter . ' AND ' . $searchFilter;
            else
                $query .= ($servicerType ? $servicerFilter : $searchFilter);
        }

        $query .= ' LIMIT :limit OFFSET :offset';

        //Query execution
        $stmt = $this->db->prepare($query);
        $stmt->bindParam('limit', $limit);
        $stmt->bindParam('offset', $offset);
        
        if($servicerType)
            $stmt->bindParam('servicer_type', $servicerType);
        
        if($searchQuery)
            $stmt->bindValue('search_query', "%$searchQuery%", PDO::PARAM_STR);
        
        $stmt->execute();
        $servicePosts = array();
        
        //Creating json array
        while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $photosStmt = $this->db->prepare('SELECT photo_reference, alt_text, caption 
                                                FROM service_photo 
                                                WHERE service_id = :service_id');
            $photosStmt->bindParam('service_id', $data['service_id']);
            $photosStmt->execute();
            $tagsStmt = $this->db->prepare('SELECT tag FROM service_tag WHERE service_id = :service_id');
            $tagsStmt->bindParam('service_id', $data['service_id']);
            $tagsStmt->execute();
            $servicePhotos = $photosStmt->fetchAll();
            $serviceTags = $tagsStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $servicePosts[] = array(
                'service_id' => $data['service_id'],
                'servicer_id' => $data['servicer_id'],
                'servicer_username' => $data['username'],
                'title' => $data['title'],
                'description' => $data['description'],
                'avg_price' => $data['avg_price'],
                'photos' => (!$servicePhotos) ? null : $servicePhotos,
                'tags' => (!$serviceTags) ? null : $serviceTags
            );
        }
        return json_encode(array_values($servicePosts));
    }
}

?>