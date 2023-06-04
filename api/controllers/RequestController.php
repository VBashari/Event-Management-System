<?php

require_once __DIR__ . '/interfaces/IController.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../utils/utils.php';

//TODO auth check

/**
 * Endpoints:
 *      GET POST        requests
 *      GET POST        requests?limit={}&offset={}
 *      GET PATCH DEL   requests/{id}
 *      GET             requests/user/{id}
 *      GET             requests/user/{id}?limit={}&offset={}
 *      GET             requests/user/{id}/incoming
 *      GET             requests/user/{id}/incoming?limit={}&offset={}
 */

class RequestController implements IController {
    private static $errors;

    private function __construct() {}

    /**
     * Get all requests from specified user in URI (optional pagination)
     */
    public static function getAllBy($limitQueries = null) {
        try {
            http_response_code(200);
            return Request::getAllBy((int) getURIparam(3), $limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Get all requests for specified user (servicer of request) in URI (optional pagination)
     */
    public static function getAllUndeclinedFor($limitQueries = null) {
        try {
            http_response_code(200);
            return Request::getAllUndeclinedFor((int) getURIparam(3), $limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Get all requests (optional pagination)
     */
    public static function getAll($limitQueries = null) {
        try {
            http_response_code(200);
            return Request::getAll($limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Get request with specified id in URI
     */
    public static function get() {
        try{
            http_response_code(200);
            return Request::get((int) getURIparam(2));
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
        self::$errors = [];

        if(!isset($_POST['requester_id']))
            self::$errors['requester_id'] = 'Required value';
        
        if(!isset($_POST['servicer_id']))
            self::$errors['servicer_id'] = 'Required value';

        self::validateTitle($_POST['title'] ?? null);
        self::validateDescription($_POST['description'] ?? null);
        self::validateStatus($_POST['status'] ?? null);
        $errorDate = validateDate($_POST['scheduled_date'] ?? null);
        
        if($errorDate)
            self::$errors['scheduled_date'] = $errorDate;
        
        if(self::$errors)
            exitError(400, self::$errors);

        try {
            $input = [
                'requester_id' => $_POST['requester_id'],
                'servicer_id' => $_POST['servicer_id'],
                'title' => $_POST['title'],
                'scheduled_date' => $_POST['scheduled_date']
            ];

            if(isset($_POST['description']))
                $input['description'] = $_POST['description'];

            if(isset($_POST['status']))
                $input['status'] = $_POST['status'];

            Request::$baseModel->insertUserCheck($input, 'servicer_id');
            http_response_code(201);
        } catch(\Exception $ex) {
            if($ex->getCode() == 23000)
                exitError(400, 'You already have a request to a servicer with this date & time');

            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate update data and, if no errors occur, perform update
     */
    public static function update() {
        self::$errors = [];
        $data = json_decode(file_get_contents('php://input'), true);

        //Data validation
        if(isset($data['title'])) {
            self::validateTitle($data['title']);
            $update['title'] = $data['title'];
        }

        if(isset($data['description'])) {
            self::validateDescription($data['description']);
            $update['description'] = $data['description'];
        }

        if(isset($data['scheduled_date'])) {
            $errorDate = validateDate($data['scheduled_date']);
            $update['scheduled_date'] = $data['scheduled_date'];

            if($errorDate)
                self::$errors['scheduled_date'] = $errorDate;
        }

        if(isset($data['status'])) {
            self::validateStatus($data['status']);
            $update['status'] = $data['status'];
        }

        if(self::$errors)
            exitError(400, self::$errors);

        //Perform update
        try {
            if(isset($update)) {
                Request::$baseModel->update($update, ['request_id' => (int) getURIparam(2)]);
                http_response_code(200);
            } else
                exitError(400, 'Update parameters cannot be empty');
        } catch(\Exception $ex) {
            if($ex->getCode() == 23000)
                exitError(400, 'You already have a request to a servicer with this date & time');

            exitError(400, $ex->getMessage());
        }
    }

    public static function delete() {
        try {
            http_response_code(204);
            Request::delete((int) getURIparam(2));
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    //Auxiliary functions

    private static function validateTitle($title) {
        if(!$title)
            self::$errors['title'] = 'Required value';
        elseif(strlen($title) < 3 || strlen($title) > 120)
            self::$errors['title'] = 'Invalid title (Accepted values: 3-120 characters)';
    }

    private static function validateDescription($description) {
        if($description && strlen($description) > 65535)
            self::$errors['description'] = 'Invalid description (Accepted values: 65,535 chars. max';
    }

    private static function validateStatus($status) {
        if($status && !in_array($status, [-1, 0, 1]))
            self::$errors['status'] = 'Invalid status (acceptable values: -1 (rejected), 0 (unevaluated), 1 (accepted)';
    }
}