<?php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/../utils/UserType.php';

class Event {
    public static $baseModel;
    private static $eventVendorModel;

    public static function __constructStatic() {
        self::$baseModel = new BaseModel('event');
        self::$eventVendorModel = new BaseModel('event_vendor');
    }

    /**
     * Insert new event with the specified values
     * 
     * @param array $parameters: key-value input of columns and values
     * @return boolean
     */
    public static function insert(array $parameters) {
        try {
            return self::$baseModel->insertUserCheck($parameters, 'organizer_id');
        } catch(InvalidArgumentException $ex) {
            throw $ex;
        } catch(PDOException $ex) {
            throw $ex;
        }
    }

    // ??
    /**
     * Insert new event vendor
     * 
     * @param array $oarameters: key-value input of columns and values
     * @return boolean
     */
    public static function insertVendor(array $parameters) {
        try {
            if(!isset($parameters['vendor_id']) || self::$baseModel->checkUserType($parameters['vendor_id']) != UserType::VENDOR->value)
                throw new InvalidArgumentException('Only vendor-type users can be added as a vendor to an event');

            return self::$eventVendorModel->insert($parameters);
        } catch(InvalidArgumentException $ex) {
            throw $ex;
        } catch(PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * Get specified event record
     * 
     * @param integer $eventId
     * @param array
     */
    public static function get($eventId) {
        try {
            return self::$baseModel->get('event_id', $eventId);
        } catch(\Exception $ex) {
            throw $ex;
        }
    }

    public static function getAll($limit = null, $offset = null) {
        $query = 'SELECT e.* FROM ' . self::$baseModel->tableName . ' e'
                . ' INNER JOIN user ON user.user_id = e.requester_id';

        if(isset($limit) && isset($offset)) {
            $query .= ' LIMIT ? OFFSET ?';
            $bindings = [$limit, $offset];
        }
        
        $stmt = self::$baseModel->db->prepare($query);

        try {
            $stmt->execute($bindings ?? null);
        } catch(PDOException $ex) {
            throw $ex;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Get the vendors hired for each event
        for($i = 0; $i < count($result); $i++) {
            $vendors = self::getAllVendorsFrom($result[$i]['event_id']);
            
            if($vendors)
                $result[$i]['vendors'] = $vendors;
        }

        return $result;
    }

    /**
     * Get all events by a specific user (optional pagination)
     * 
     * @param integer $userId
     * @param integer $limit
     * @param integer $offset
     * @return array query results
     */
    public static function getAllBy($userId, $limit = null, $offset = null) {
        if(isset($limit) && isset($offset)) {
            $limiterQuery = 'LIMIT :limit OFFSET :offset';
            $limiterValues = ['limit' => $limit, 'offset' => $offset];
        }

        //Return results customized in relation to the user's type
        switch(self::$baseModel->checkUserType($userId)) {
            case UserType::USER->value:
                return self::getAllByUser($userId, $limiterQuery ?? null, $limiterValues ?? null);
            case UserType::VENDOR->value:
                return self::getAllByVendor($userId, $limiterQuery ?? null, $limiterValues ?? null);
            case UserType::EVENT_ORGANIZER->value:
                return self::getAllByOrganizer($userId, $limiterQuery ?? null, $limiterValues ?? null);
        }
    }
    
    /**
     * Get all events by a specific user in the specified month and year
     * 
     * @param integer $userId
     * @param integer $month
     * @param integer $year
     * @return array query results
     */
    public static function getMonthlyAllBy($userId, $month, $year) {
        $limiterQuery = 'AND MONTH(e.scheduled_date) = :month AND YEAR(e.scheduled_date) = :year';
        $limiterValues = ['month' => $month, 'year' => $year];

        switch(self::$baseModel->checkUserType($userId)) {
            case UserType::USER->value:
                return self::getAllByUser($userId, $limiterQuery, $limiterValues);
            case UserType::VENDOR->value:
                return self::getAllByVendor($userId, $limiterQuery, $limiterValues);
            case UserType::EVENT_ORGANIZER->value:
                return self::getAllByOrganizer($userId, $limiterQuery, $limiterValues);
        }
    }

    /**
     * Get all events by a specified user (optional pagination or calendar filtering)
     * 
     * @param integer $userId
     * @param string $limiterQuery: query section specifying pagination or calendar filter
     * @param array $limiterValues: key-value pairs of the limiter
     * @return array query results
     */
    private static function getAllByUser($userId, string $limiterQuery = null, array $limiterValues = null) {
        $query = 'SELECT e.event_id, e.organizer_id, e.title, e.scheduled_date, user.username AS organizer_username FROM '
                . self::$baseModel->tableName . ' e'
                . ' INNER JOIN user ON user.user_id = e.organizer_id WHERE e.requester_id = :user_id ' . $limiterQuery;
        $bindings = ['user_id' => $userId];

        if($limiterValues)
            $bindings = array_merge($bindings, $limiterValues);
            
        $stmt = self::$baseModel->db->prepare($query);

        try {
            $stmt->execute($bindings);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all events by a specified vendor (optional pagination or calendar filtering): returns them separated based on 
     * requester type (user or event organizer)
     * 
     * @param integer $vendorId
     * @param string $limiterQuery: query section specifying pagination or calendar filter
     * @param array $limiterValues: key-value pairs of the limiter
     * @return array query results
     */
    private static function getAllByVendor($vendorId, string $limiterQuery = null, array $limiterValues = null) {
        //Query for getting all event requested by a user
        $userQuery = 'SELECT e.event_id, e.requester_id, e.title, e.scheduled_date, user.username AS requester_username FROM ' 
                    . self::$baseModel->tableName . ' e'
                    . ' INNER JOIN user ON user.user_id = e.requester_id WHERE e.organizer_id = :vendor_id ' . $limiterQuery;

        //Query for getting all event requested by an event organizer
        $orgQuery = 'SELECT e.event_id, e.organizer_id AS requester_id, e.title, e.scheduled_date, user.username as requester_username'
                    . ' FROM ' . self::$baseModel->tableName . ' e'
                    . ' INNER JOIN user ON user.user_id = e.organizer_id'
                    . ' INNER JOIN event_vendor ON event_vendor.event_id = e.event_id'
                    . ' WHERE event_vendor.vendor_id = :vendor_id ' . $limiterQuery;

        $bindings = ['vendor_id' => $vendorId];

        if($limiterValues)
            $bindings = array_merge($bindings, $limiterValues);

        $userStmt = self::$baseModel->db->prepare($userQuery);
        $orgStmt = self::$baseModel->db->prepare($orgQuery);

        try {
            $userStmt->execute($bindings);
            $orgStmt->execute($bindings);
        } catch(PDOException $ex) {
            throw $ex;
        }

        return ['user_events' => $userStmt->fetchAll(PDO::FETCH_ASSOC), 'org_events' => $orgStmt->fetchAll(PDO::FETCH_ASSOC)];
    }

    /**
     * Get all events by a specified event organizer (optional pagination or calendar filtering)
     * 
     * @param integer $orgId
     * @param string $limiterQuery: query section specifying pagination or calendar filter
     * @param array $limiterValues: key-value pairs of the limiter
     * @return array query results
     */
    private static function getAllByOrganizer($orgId, string $limiterQuery = null, array $limiterValues = null) {
        $stmt = self::$baseModel->db->prepare('SELECT e.event_id, e.requester_id, e.title, e.scheduled_date, user.username AS requester_username'
                                            . ' FROM ' . self::$baseModel->tableName . ' e'
                                            . ' INNER JOIN user ON user.user_id = e.requester_id WHERE e.organizer_id = :org_id ' . $limiterQuery);

        $bindings = ['org_id' => $orgId];

        if($limiterValues)
            $bindings = array_merge($bindings, $limiterValues);

        try {
            $stmt->execute($bindings);
        } catch(PDOException $ex) {
            throw $ex;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Get the vendors hired for each event
        for($i = 0; $i < count($result); $i++) {
            $vendors = self::getAllVendorsFrom($result[$i]['event_id']);
            
            if($vendors)
                $result[$i]['vendors'] = $vendors;
        }

        return $result;
    }

    /**
     * Get all vendors from the specified event
     * 
     * @param integer $eventId
     * @return array query results
     */
    private static function getAllVendorsFrom($eventId) {
        $stmt = self::$baseModel->db->prepare('SELECT vendor.vendor_id, user.username as vendor_username'
                                                . ' FROM event_vendor as vendor INNER JOIN user ON user.user_id = vendor.vendor_id'
                                                . ' WHERE vendor.event_id = ?');

        try {
            $stmt->execute([$eventId]);
        } catch(PDOException $ex) {
            throw $ex;
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

Event::__constructStatic();