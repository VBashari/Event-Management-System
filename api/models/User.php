<?php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/../utils/UserType.php';

class User {
    private static $baseModel = null;

    public static function __constructStatic() {
        self::$baseModel = new BaseModel('user');
    }

    public static function getBase() {
        return self::$baseModel;
    }

    /**
     * Get all user records (optional pagination)
     * 
     * @param integer $limit: no. of records to return
     * @param integer $offset: starting record
     * @return array query results
     */

    public static function getAll($limit = null, $offset = null) {
        try {
            return self::$baseModel->getAll($limit, $offset, ['password']);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get specified record
     * 
     * @param integer $userId
     * @return query results
     */

    public static function get($userId) {
        try {
            return self::$baseModel->get('user_id', $userId, ['password']);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get all user records with user type 'USER' (optional pagination)
     * 
     * @param integer $limit: no. of records to return
     * @param integer $offset: starting record
     * @return query results array
     */
    public static function getAllUsers($limit = null, $offset = null) {
        $query = 'SELECT user_id, username, email FROM ' . self::$baseModel->tableName
                . ' WHERE user_type = ?';
        $bindingArray = array(UserType::USER->value);

        if(isset($limit) && isset($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            array_push($bindingArray, $limit, $offset);
        }

        $stmt = self::$baseModel->db->prepare($query);

        try {
            $stmt->execute($bindingArray);
        } catch(PDOException $ex) {
            throw $ex;
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all user records with user type 'VNDR' or 'ORG' (optional pagination)
     * 
     * @param integer $limit: no. of records to return
     * @param integer $offset: starting record
     * @return query results array
     */
    public static function getAllServicers($limit = null, $offset = null) {
        $query = 'SELECT user_id, user_type, username, email FROM ' . self::$baseModel->tableName
                . ' WHERE user_type = ? OR user_type = ?';
        $bindingArray = array(UserType::VENDOR->value, UserType::EVENT_ORGANIZER->value);

        if(isset($limit) && isset($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            array_push($bindingArray, $limit, $offset);
        }
        
        $stmt = self::$baseModel->db->prepare($query);

        try {
            $stmt->execute($bindingArray);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Checks if the given username exists in the database
     * 
     * @param string $username: username
     * @return boolean
     */
    public static function doesUsernameExist($username): bool {
        $stmt = self::$baseModel->db->prepare('SELECT username FROM ' . self::$baseModel->tableName . ' WHERE username = ?');

        try {
            $stmt->execute(array($username));
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->rowCount() == 0 ? false : true;
    }

    //?? TODO needs hashing
    public static function changePassword($userId, $oldPassword, $newPassword) {}
}

User::__constructStatic();