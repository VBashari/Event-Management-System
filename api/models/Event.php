<?php
    require_once __DIR__ . '/Model.php';

    class Event extends Model {
        public function __construct() {
            parent::__construct('event');
        }

        public function insert(array $parameters) {
            try {
                return parent::insertUserCheck($parameters, 'requester_id', 'organizer_id');
            } catch(InvalidArgumentException $ex) {
                throw $ex;
            } catch(PDOException $ex) {
                throw $ex;
            }
        }

        /*
        public function getAllBy($userId) {
            $stmt = $this->db->prepare(generateGetQuery(parent::checkUserType($userId)));
            $stmt->bindParam('user_id', $vendorId);

            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
        }
        */
        
        public function getAllMonthlyBy($userId, $month, $year) {
            $query = generateGetQuery(parent::checkUserType($userId)) . 
                    ' AND MONTH(scheduled_date) = :month AND YEAR(scheduled_date) = :year';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('user_id', $vendorId);
            $stmt->bindParam('month', $month);
            $stmt->bindParam('year', $year);

            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return json_encode(array_values($stmt->fetchAll(PDO::FETCH_ASSOC)));
        }

        private function generateGetQuery($userType): string {
            $query = "SELECT $this->tableName.*, user.username AS servicer_username FROM event INNER JOIN user";

            switch ($userType) {
                case 'USER':
                    $query .= ' ON user.user_id = event.organizer_id
                                WHERE event.requester_id = :user_id';
                    break;
                case 'VNDR':
                    $query .= ' ON user.user_id = event.requester_id
                                INNER JOIN event_vendor vendor
                                ON event.event_id = vendor.event_id
                                WHERE vendor.vendor_id = :user_id';
                    break;
                case 'ORG':
                    $query .= ' ON user.user_id = event.requester_id
                                WHERE event.organizer_id = :user_id';
                    break;
                default:
                    throw new InvalidArgumentException('User ID not found');
            }

            return $query;
        }
    }

    //TODO test
    // $temp = new Event();
    // $temp->insert(array('requester_id'=>4, 'organizer_id'=>8, 'title'=>'testing', 'scheduled_date'=> '2030-03-12'));
    // echo $temp->getAll(8);