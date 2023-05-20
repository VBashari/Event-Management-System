<?php

    require_once __DIR__ . '/Model.php';

    class Request extends Model {
        private $db;

        public function __construct() {
            $this->db = Connector::getConnector();
        }

        public function insert(array $parameters) {
            try {
                return parent::insertUserCheck($parameters, 'requester_id', 'servicer_id');
            } catch(InvalidArgumentException $ex) {
                throw $ex;
            } catch(PDOException $ex) {
                throw $ex;
            }
        }

        public function getBy($userId) {
            // $stmt = $this->db->prepare("SELECT ");
        }

        public function getAllPaginatedBy($userId, $limit, $offset) {

        }
    }