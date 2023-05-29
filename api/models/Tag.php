<?php

require_once __DIR__ . '/BaseModel.php';

class Tag {
    public static $baseModel;

    public static function __constructStatic() {
        self::$baseModel = new BaseModel('service_tag');
    }

    public static function getAllBy($recordId) {
        $stmt = self::$baseModel->db->prepare('SELECT tag FROM ' . self::$baseModel->tableName . ' WHERE service_id = ?');
            
        try {
            $stmt->execute([$recordId]);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function delete($serviceId, $tag) {
        try {
            return self::$baseModel->delete(['service_id' => $serviceId, 'tag' => $tag]);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }
}

Tag::__constructStatic();