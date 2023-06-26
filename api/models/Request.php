<?php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/../utils/UserType.php';

class Request {
    public static $baseModel;

    public static function __constructStatic() {
        self::$baseModel = new BaseModel('request');
    }

    public static function insert(array $parameters) {
        if(!$parameters)
            throw new InvalidArgumentException("Insert parameters cannot be empty");

        if(self::$baseModel->checkUserType($parameters['servicer_id']) == UserType::USER->value)
            throw new InvalidArgumentException('User types cannot take requests');

        if(is_null($parameters['description']))
            unset($parameters['description']);

        try {
            return self::$baseModel->insert($parameters);
        } catch(InvalidArgumentException $ex) {
            throw $ex;
        } catch(PDOException $ex) {
            throw $ex;
        }
    }

    public static function delete($requestID) {
        try {
            self::$baseModel->delete(['request_id' => $requestID]);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get specified request record
     * 
     * @param integer $requestId
     * @param array
     */
    public static function get($requestId) {
        try {
            return self::$baseModel->get('request_id', $requestId);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get all requests (optional pagination)
     * 
     * @param integer $limit
     * @param integer $offset
     * @return array query results
     */
    public static function getAll($limit = null, $offset = null) {
        try {
            return self::$baseModel->getAll($limit, $offset);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get all requests by specified servicer (optional pagination)
     * 
     * @param integer $userID
     * @param integer $limit
     * @param integer $offset
     * @return array query results
     */
    public static function getAllBy($userID, $limit = null, $offset = null) {
        $query = 'SELECT ' . self::$baseModel->tableName . '.*, user.username as servicer_username'
                . ' FROM ' . self::$baseModel->tableName
                . ' INNER JOIN user ON user.user_id = ' . self::$baseModel->tableName . '.servicer_id'
                . ' WHERE requester_id = ?';
        $bindings = [$userID];

        if(isset($limit) && isset($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            array_push($bindings, $limit, $offset);
        }

        $stmt = self::$baseModel->db->prepare($query);
        
        try {
            $stmt->execute($bindings);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all accepted or unevaluated requests for servicer-type users
     * 
     * @param integer $userID
     * @param integer $limit
     * @param integer $offset
     * @return array query results
     */
    public static function getAllUnevaluatedFor($userID, $limit = null, $offset = null) {
        if(self::$baseModel->checkUserType($userID) == UserType::USER->value)
            throw new InvalidArgumentException('User types cannot have incoming requests');

        $query = 'SELECT ' . self::$baseModel->tableName . '.*, user.username as requester_username'
                . ' FROM ' . self::$baseModel->tableName
                . ' INNER JOIN user ON user.user_id = ' . self::$baseModel->tableName . '.requester_id'
                . ' WHERE servicer_id = ? AND status = 0';
        $bindings = [$userID];

        if(isset($limit) && isset($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            array_push($bindings, $limit, $offset);
        }

        $stmt = self::$baseModel->db->prepare($query);
        
        try {
            $stmt->execute($bindings);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

Request::__constructStatic();