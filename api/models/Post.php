<?php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Photo.php';

class Post {
    public static $baseModel;

    public static function __constructStatic() {
        self::$baseModel = new BaseModel('post');
    }

    public static function insert(array $parameters) {
        if(!$parameters)
            throw new InvalidArgumentException("Insert parameters cannot be empty");

        if(self::$baseModel->checkUserType($parameters['servicer_id']) == UserType::USER->value)
            throw new InvalidArgumentException('User types cannot make posts');

        try {
            return self::$baseModel->insert($parameters);
        } catch(InvalidArgumentException $ex) {
            throw $ex;
        } catch(PDOException $ex) {
            throw $ex;
        }
    } 

    /**
     * Get specified post record
     * 
     * @param integer $postId
     * @param array
     */
    public static function get($postId) {
        try {
            return self::$baseModel->get('post_id', $postId);
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
        if(self::$baseModel->checkUserType($userId) == UserType::USER->value)
            throw new InvalidArgumentException('User types do not have posts');
        
        $query = 'SELECT * FROM ' . self::$baseModel->tableName . ' WHERE servicer_id = ?';
        $binding = [$userId];

        if(isset($limit) && isset($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            array_push($binding, $limit, $offset);
        }

        $stmt = self::$baseModel->db->prepare($query);

        try {
            $stmt->execute($binding);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }
}

Post::__constructStatic();