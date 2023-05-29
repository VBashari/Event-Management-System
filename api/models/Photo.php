<?php

require_once __DIR__ . '/BaseModel.php';

class Photo extends BaseModel {
    private $typeField; //post photo or service photo

    public function __construct($typeField, $tableName) {
        $this->typeField = $typeField;
        parent::__construct($tableName);
    }

    public function getAllBy($recordId) {
        $stmt = $this->db->prepare("SELECT photo_reference, alt_text, caption FROM {$this->tableName}"
                                . " WHERE {$this->typeField}_id = ?");
            
        try {
            $stmt->execute([$recordId]);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReferencesBy($recordId) {
        if(self::$baseModel->checkUserType($parameters[$this->typeField . '_id']) == UserType::USER->value)
            throw new InvalidArgumentException('User types cannot make posts');
        
        $stmt = $this->db->prepare("SELECT {$this->tableName}.photo_reference FROM {$this->tableName}"
                                . " INNER JOIN {$this->typeField} ON {$this->typeField}.{$this->typeField}_id = {$this->tableName}.{$this->typeField}_id"
                                . " WHERE {$this->typeField}.servicer_id = ?");
                        
        try {
            $stmt->execute([$recordId]);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll();
    }

    public function insert(array $parameters) {
        if(!$parameters)
            throw new InvalidArgumentException("Insert parameters cannot be empty");

        if(is_null($parameters['alt_text']))
            unset($parameters['alt_text']);

        if(is_null($parameters['caption']))
            unset($parameters['caption']);
        
        try {
            return parent::insert($parameters);
        } catch(InvalidArgumentException $ex) {
            throw $ex;
        } catch(PDOException $ex) {
            throw $ex;
        }
    }

    public function delete($photoReference) {
        try {
            return parent::delete(['photo_reference' => $photoReference]);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }
}