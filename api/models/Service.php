<?php

require_once __DIR__ . '/BaseModel.php';

class Service {
    public static $baseModel;

    public static function __constructStatic() {
        self::$baseModel = new BaseModel('service');
    }

    /**
     * Get specified request record
     * 
     * @param integer $serviceID
     * @param array
     */
    public static function get($serviceID) {
        try {
            return self::$baseModel->get('service_id', $serviceID);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get all posts by specified servicer (optional pagination)
     * 
     * @param integer $userId
     * @param integer $limit
     * @param integer $offset
     * @return array query results
     */
    public static function getAllBy($userId, $limit = null, $offset = null) {
        $query = 'SELECT ' . self::$baseModel->tableName . '.* FROM ' . self::$baseModel->tableName
                . ' WHERE servicer_id = ?';
        $bindings = [$userId];

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

Service::__constructStatic();