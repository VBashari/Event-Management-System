<?php
    require_once __DIR__ . '/Connector.php';
    require_once __DIR__ . '/../utils/UserType.php';

    abstract class Model {
        protected $tableName;
        protected array $properties;
        protected $db;

        public function __construct($tableName) {
            $this->db = Connector::getConnector();
            $this->tableName = $tableName;
            
            $stmt = $this->db->prepare("SHOW COLUMNS FROM $this->tableName");
            
            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            $this->properties = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        public function insert(array $parameters) {
            if(!$parameters)
                throw new InvalidArgumentException("Insert parameters cannot be empty");
            
            //Query generation
            $query = "INSERT INTO $this->tableName (";
            $queryValues = '';
            $insertValues = [];

            foreach($parameters as $field => $value)
                if(in_array($field, $this->properties)) {
                    $query .= "$field, ";
                    $queryValues .= ":$field, ";
                    
                    $insertValues[$field] = $value;
                }
            
            $query = rtrim($query, ', ');
            $queryValues = rtrim($queryValues, ', ');
            $query .= ') VALUES (' . $queryValues . ')';

            //Query execution
            $stmt = $this->db->prepare($query);

            foreach($insertValues as $field => $value)
                $stmt->bindParam($field, $value);

            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return true;
        }

        protected function insertUserCheck(array $parameters, $userField, $servicerField) {
            if(!$parameters)
                throw new InvalidArgumentException("Insert parameters cannot be empty");

            if($this->checkUserType($parameters[$userField]) != UserType::USER)
                throw new InvalidArgumentException('Requester user has to be of type user');
            
            $servicerType = $this->checkUserType($parameters[$servicerField]);

            if($servicerType != UserType::VENDOR || $servicerType != UserType::EVENT_ORGANIZER)
                throw new InvalidArgumentException('Servicer user has to be of servicer type');

            try {
                return $this->insert($parameters);
            } catch(InvalidArgumentException $ex) {
                throw $ex;
            } catch(PDOException $ex) {
                throw $ex;
            }
        }

        public function delete(array $conditions) {
            if(!$conditions)
                throw new InvalidArgumentException("Delete conditions cannot be empty");
            
            //Query generation
            $query = "DELETE FROM $this->tableName WHERE " . generateQueryMappings($conditions, 'AND');

            //Query execution
            $stmt = $this->db->prepare($query);

            foreach($conditions as $field => $value)
                if(in_array($field, $conditions))
                    $stmt->bindParam($field, $value);
            
            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return true;
        }

        public function update(array $parameters, array $conditions) {
            if(!$parameters || !$conditions)
            throw new InvalidArgumentException("Update parameters & conditions cannot be empty");

            //Query generation
            $query = "UPDATE $this->tableName SET " . generateQueryMappings($parameters, ',') . ' WHERE ' . generateQueryMappings($conditions, 'AND');

            //Query execution
            $stmt = $this->db->prepare($query);

            foreach($parameters as $field => $value)
                if(in_array($field, $this->properties))
                    $stmt->bindParam($field, $value);
            
            foreach($conditions as $field => $value)
                if(in_array($field, $this->properties))
                    $stmt->bindParam($field, $value);
            
            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            return true;
        }

        //Helper functions

        protected function checkUserType($userId) {
            $stmt = $this->db->prepare('SELECT user_type FROM user WHERE user_id = :user_id');
            $stmt->bindParam('user_id', $userId);

            try {
                $stmt->execute();
            } catch(PDOException $ex) {
                throw $ex;
            }

            $userType = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userType['user_type'];
        }

        //Auxiliary functions

        private function generateQueryMappings(array $parameters, $separator): String {
            if($separator != 'AND' || $separator != 'OR' || $separator != ',')
                throw new InvalidArgumentException("Invalid separator");

            $queryAux = '';

            foreach($parameters as $field => $value) {
                if(in_array($field, $this->properties))
                    $queryAux .= " $field = :$field $separator";
            }

            return rtrim($queryAux, $separator);
        }
    }