<?php

require_once __DIR__ . '/Connector.php';
require_once __DIR__ . '/../utils/UserType.php';

class BaseModel {
    public $tableName;
    protected array $properties;
    public $db;

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
        $stmt->closeCursor();
    }

    /**
     * Get all records (optional pagination)
     * 
     * @param array $excludeFields: fields to be excluded from the query
     * @param integer $limit:       no. of records to return
     * @param integer $offset:      starting record
     * @return array query results 
     */
    public function getAll($limit = null, $offset = null, array $excludeFields = null) {
        //Query generation
        $query = 'SELECT';

        if($excludeFields) {
            foreach($this->properties as $field)
                if(!in_array($field, $excludeFields))
                    $query .= " $field,";
        } else
            foreach($this->properties as $field)
                $query .= " $field,";
        
        $query = rtrim($query, ',') . " FROM $this->tableName";
        $bindingArray = [];

        if(!is_null($limit) && !is_null($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            $bindingArray = array($limit, $offset);
        }

        //Query execution
        $stmt = $this->db->prepare($query);
        
        try {
            $stmt->execute($bindingArray);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Get single user from specified ID
    *
    * @param array $excludeFields:  fields to exclude from the query
    * @param string $idField:       the name of the ID field for this table
    * @param integer $recordId:     record's ID
    * @return array
    */
    public function get($idField, $recordId, array $excludeFields = null) {
        if(is_null($idField) || is_null($recordId))
            throw new InvalidArgumentException("ID field and the record's ID cannot be empty");
        
        //Query generation
        $query = 'SELECT';
        
        if($excludeFields) {
            foreach($this->properties as $field)
                if(!in_array($field, $excludeFields))
                    $query .= " $field,";
        } else
            foreach($this->properties as $field)
                $query .= " $field,";

        if(isset($excludeFields))
            str_replace($excludeFields, '', $query);

        $query = rtrim($query, ',') . " FROM $this->tableName WHERE $idField = ?";

        //Query execution
        $stmt = $this->db->prepare($query);

        try {
            $stmt->execute(array($recordId));
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert new record
     * 
     * @param array $parameters: key-value input of columns and values
<<<<<<< HEAD
     * @return boolean
=======
     * @return string|false
>>>>>>> api_rewrite
     */
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
<<<<<<< HEAD

        //Query execution
        $stmt = $this->db->prepare($query);

        foreach($insertValues as $field => $value)
            $stmt->bindParam($field, $value);

        try {
            $stmt->execute();
=======
        
        //Query execution
        $stmt = $this->db->prepare($query);

        try {
            $stmt->execute($insertValues);
            return $this->db->lastInsertId();
>>>>>>> api_rewrite
        } catch(PDOException $ex) {
            throw $ex;
        }

        $stmt->closeCursor();
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> api_rewrite
    }

    /**
     * Insert new record with the specified servicer ID field. Checks if the 
     * user's ID matches the type required by the field: if not, it throws an error
     * 
     * @param array $parameters:     key-value input of columns and values
     * @param string $servicerField: name of the servicer field in this table
<<<<<<< HEAD
     * @return boolean
     */
    protected function insertUserCheck(array $parameters, $servicerField) {
=======
     * @return string|false
     */
    public function insertUserCheck(array $parameters, $servicerField) {
>>>>>>> api_rewrite
        if(!$parameters)
            throw new InvalidArgumentException("Insert parameters cannot be empty");
        
        $servicerType = $this->checkUserType($parameters[$servicerField]);

<<<<<<< HEAD
        if($servicerType != UserType::VENDOR || $servicerType != UserType::EVENT_ORGANIZER)
=======
        if($servicerType === false)
            throw new InvalidArgumentException('User does not exist');
        if($servicerType != UserType::VENDOR->value && $servicerType != UserType::EVENT_ORGANIZER->value)
>>>>>>> api_rewrite
            throw new InvalidArgumentException('Servicer user has to be of servicer type');

        try {
            return $this->insert($parameters);
        } catch(InvalidArgumentException $ex) {
            throw $ex;
        } catch(PDOException $ex) {
            throw $ex;
        }

        $stmt->closeCursor();
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> api_rewrite
    }

    /***
     * Update an existing record
     * 
     * @param array $parameters: key-value input of columns and values
     * @param array $conditions: key-value input of columns and values specifying record(s) to be updated
     * @return boolean
     */
<<<<<<< HEAD
    protected function update(array $parameters, array $conditions) {
=======
    public function update(array $parameters, array $conditions) {
>>>>>>> api_rewrite
        if(!$parameters || !$conditions)
            throw new InvalidArgumentException("Update parameters & conditions cannot be empty");

        //Query generation
<<<<<<< HEAD
        $query = "UPDATE $this->tableName SET " . generateQueryMappings($parameters, ',') . ' WHERE ' . generateQueryMappings($conditions, 'AND');

=======
        $query = "UPDATE $this->tableName SET " . $this->generateQueryMappings($parameters, ',') . ' WHERE ' . $this->generateQueryMappings($conditions, 'AND');
        
>>>>>>> api_rewrite
        //Query execution
        $stmt = $this->db->prepare($query);

        foreach($parameters as $field => $value)
            if(in_array($field, $this->properties))
<<<<<<< HEAD
                $stmt->bindParam($field, $value);
        
        foreach($conditions as $field => $value)
            if(in_array($field, $this->properties))
                $stmt->bindParam($field, $value);
        
        try {
            $stmt->execute();
=======
                $values[$field] = $value;
        
        foreach($conditions as $field => $value)
            if(in_array($field, $this->properties))
                $values[$field] = $value;
        
        try {
            $stmt->execute($values);
            return $stmt->rowCount() > 0;
>>>>>>> api_rewrite
        } catch(PDOException $ex) {
            throw $ex;
        }

        $stmt->closeCursor();
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> api_rewrite
    }

    /**
     * Delete an existing record
     * 
     * @param array $conditions: key-value pairs input of columns and values specifying record to be deleted
     * @return boolean 
     */
<<<<<<< HEAD
    protected function delete(array $conditions) {
=======
    public function delete(array $conditions) {
>>>>>>> api_rewrite
        if(!$conditions)
            throw new InvalidArgumentException("Delete conditions cannot be empty");
        
        //Query generation
<<<<<<< HEAD
        $query = "DELETE FROM $this->tableName WHERE " . generateQueryMappings($conditions, 'AND');
=======
        $query = "DELETE FROM $this->tableName WHERE " . $this->generateQueryMappings($conditions, 'AND');
>>>>>>> api_rewrite

        //Query execution
        $stmt = $this->db->prepare($query);

        foreach($conditions as $field => $value)
<<<<<<< HEAD
            if(in_array($field, $conditions))
                $stmt->bindParam($field, $value);
        
        try {
            $stmt->execute();
=======
            if(in_array($field, $this->properties))
                $stmt->bindParam($field, $value);
        
        
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
>>>>>>> api_rewrite
        } catch(PDOException $ex) {
            throw $ex;
        }

        $stmt->closeCursor();
<<<<<<< HEAD
        return true;
=======
        return false;
>>>>>>> api_rewrite
    }


    //Auxiliary functions

    /**
     * Gets the user type of the specified user
     * @param integer $userId: the specified user's ID
<<<<<<< HEAD
     * @return string
=======
     * @return string|false
>>>>>>> api_rewrite
     */
    public function checkUserType($userId) {
        $stmt = $this->db->prepare('SELECT user_type FROM user WHERE user_id = :user_id');
        $stmt->bindParam('user_id', $userId);

        try {
            $stmt->execute();
        } catch(PDOException $ex) {
            throw $ex;
        }

        $userType = $stmt->fetch(PDO::FETCH_ASSOC);
<<<<<<< HEAD
        return $userType['user_type'];
=======
        return $userType ? $userType['user_type'] : false;
>>>>>>> api_rewrite
    }

    /**
     * Generate string of "key = value" separated by SQL compliant separator for query creations
     * @param array $parameters: key-value input of columns and values
     * @param string $separator: symbol/operator to separate the key-value pairs
     * @return string
     */
    private function generateQueryMappings(array $parameters, $separator): string {
        if($separator != 'AND' && $separator != 'OR' && $separator != ',')
            throw new InvalidArgumentException("Invalid separator");

        $queryAux = '';

        foreach($parameters as $field => $value) {
            if(in_array($field, $this->properties))
                $queryAux .= " $field = :$field $separator";
        }

        return rtrim($queryAux, $separator);
    }
}