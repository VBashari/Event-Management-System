<?php

require_once __DIR__ . '/interfaces/IController.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../utils/utils.php';

//TODO auth check

/**
 * Endpoints:
 *      GET POST            events
 *      GET POST            events?limit={}&offset={}
 *      GET POST PATCH DEL  events/{id}
 *      GET                 events/user/{id}
 *      GET                 events/user/{id}?limit={}&offset={}
 *      GET                 events/user/{id}?month={month}&year={year}
 */

class EventController implements IController {
    private static $errors;
    private static $data;
    
    private function __construct() {}

    public static function __constructStatic() {
        self::$data = readRequestBody();
    }

    public static function getAllBy($limitQueries = null) {
        try {
            http_response_code(200);
            return Event::getAllBy((int) getURIparam(3), $limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function getMonthlyAllBy($timeQueries) {
        try {
            http_response_code(200);
            return Event::getMonthlyAllBy((int) getURIparam(3), $timeQueries['month'], $timeQueries['year']);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function getAll($limitQueries = null) {
        try {
            http_response_code(200);
            return Event::getAll($limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function get() {
        try {
            http_response_code(200);
            return Event::get((int) getURIparam(2));
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Add vendor for event with specified ID in the URI 
     */
    public static function insertVendorFor() {
        if(!isset(self::$data['vendor_id']))
            exitError(400, 'Vendor ID is required');

        try {
            Event::insertVendor(['event_id' => (int) getURIparam(2), 'vendor_id' => self::$data['vendor_id']]);
            http_response_code(201);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
        self::$errors = [];
        self::validateTitle(self::$data['title']);

        if(!self::$data['requester_id'])
            self::$errors['requester_id'] = 'Invalid requester ID (required value)';
        
        if(!self::$data['organizer_id'])
            self::$errors['organizer_id'] = 'Invalid organizer ID (required value)';

        if(!self::$data['scheduled_date'])
            self::$errors['scheduled_date'] = 'Invalid schedule date (required value)';

        $errorDate = validateDate(self::$data['scheduled_date']);

        if($errorDate)
            self::$errors['scheduled_date'] = $errorDate;

        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
            Event::insert([
                'requester_id' => self::$data['requester_id'],
                'organizer_id' => self::$data['organizer_id'],
                'title' => self::$data['title'],
                'scheduled_date' => self::$data['scheduled_date']
            ]);
            http_response_code(201);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate the specified update data and, if no errors occur, perform update
     */
    public static function update() {
        self::$errors = [];

        if($_REQUEST['title']) {
            $update['title'] = $_REQUEST['title'];
            self::validateTitle($update['title']);
        }

        if($_REQUEST['scheduled_date']) {
            $update['scheduled_date'] = $_REQUEST['scheduled_date'];
            $error = validateDate($update['scheduled_date']);

            if($error)
                self::$errors['scheduled_date'] = $error;
        }

        if(self::$errors)
            exitError(400, self::$errors);
        
        try {
            Event::$baseModel->update($update ?? null, ['event_id' => (int) getURIparam(2)]);
            http_response_code(200);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function delete() {
        try {
            Event::$baseModel->delete(['event_id' => (int) getURIparam(2)]);
            http_response_code(204);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    // Auxiliary functions

    /**
     * Check if title is 3-120 characters long: if not, adds an error to the class errors
     * 
     * @param string $title
     */
    private static function validateTitle($title) {
        if(!$title || strlen($title) < 3 || strlen($title) > 120)
            self::$errors['title'] = 'Invalid title (Accepted values: 3-120 characters)';
    }

    /**
     * Check if uri matches "events/user/{id}?month={month}&year={year}"
     * 
     * @param array $pathParts:  uri path 
     * @param array $queries:    uri queries
     * @return boolean
     */
    private static function isCalendarListUri($pathParts, $queries) {
        return count($pathParts) == 3 && is_numeric($pathParts[2]) 
            && !array_diff_key($queries, array_flip(array('month', 'year'))) 
            && is_numeric($queries['month']) && is_numeric($queries['year']);
    }
}

EventController::__constructStatic();