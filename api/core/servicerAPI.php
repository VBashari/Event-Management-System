<?php

    require_once __DIR__ . './Connector.php';

//Review: change all to static or abstract classes?
//or leave them as instances but create userid class var

class ServicerAPI {
    protected $db;
    protected $userId;

    protected function __construct($userId) {
        $this->db = Connector::getConnector();
        $this->userId = $userId;
    }

    // TODO: there is no reason for methods like this to be instance methods
    public function getRequests() {
        $stmt = $this->db->prepare('SELECT req.*, user.username AS requester_username
                                    FROM request req
                                    INNER JOIN user ON user.user_id = req.requester_id
                                    WHERE req.servicer_id = :user_id AND status = 0');

        $stmt->bindParam('user_id', $this->userId);
        $stmt->execute();

        return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }

    private function updateRequestStatus($requestId, $newStatus) {
        try {
            $stmt = $this->db->prepare('UPDATE request SET status = ? WHERE request_id = ?');
            $stmt->execute(array($newStatus, $requestId));
        } catch(PDOException $ex) {
            return $ex->getMessage();
        }
    }

    public function rejectRequest($requestId) {
        $this->updateRequestStatus($requestId, -1);
    }
    
    public function acceptRequest($requestId) {
        $this->updateRequestStatus($requestId, 1);

        //Get request info
        $requestQuery = $this->db->prepare('SELECT requester_id, servicer_id, title, scheduled_date 
                                            FROM request 
                                            WHERE request_id = :request_id');
            
        $requestQuery->bindParam('request_id', $requestId);
        $requestQuery->execute();
        
        $request = $requestQuery->fetch(PDO::FETCH_ASSOC);

        //Create event from request
        // $stmt = $this->db->prepare('INSERT INTO event (requester_id, organizer_id, title, scheduled_date) 
        //                                 VALUES (?, ?, ?, ?)');
        // $stmt->execute(array($request['requester_id'], $request['servicer_id'], $request['title'], $request['scheduled_date']));
    }

    public function getPosts() {
        $stmt = $this->db->prepare('SELECT service_id, title, description, avg_price
                                    FROM service WHERE servicer_id = :user_id');
        
        $stmt->bindParam('user_id', $this->userId);
        $stmt->execute();

        return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }
}

// $myApi = new VendorAPI();
// header('Content-Type: application/json');
// echo $myApi->getPosts(8);
?>