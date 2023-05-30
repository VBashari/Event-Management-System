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
        self::validateTitle($_POST['title']);
        self::validateDescription($_POST['description']);
        self::validateStatus($_POST['status'] ?? null);
        $errorDate = validateDate($_POST['scheduled_date']);

        if($errorDate)
            self::$errors['scheduled_date'] = $errorDate;
        
        if(self::$errors)
            return false;

        try {
            $input = [
                'requester_id' => $_POST['requester_id'],
                'servicer_id' => $_POST['servicer_id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'scheduled_date' => $_POST['scheduled_date']
            ];

            if($_POST['status'])
                $input['status'] = $_POST['status'];

            Request::insert($input);
            http_response_code(201);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate update data and, if no errors occur, perform update
     */
    public static function update() {
        //Data validation
        if($_REQUEST['title']) {
            self::validateTitle($_REQUEST['title']);
            $update['title'] = $_REQUEST['title'];
        }

        if($_REQUEST['description']) {
            self::validateDescription($_REQUEST['description']);
            $update['description'] = $_REQUEST['description'];
        }

        if($_REQUEST['scheduled_date']) {
            $error = validateDate($_REQUEST['scheduled_date']);
            $update['scheduled_date'] = $_REQUEST['scheduled_date'];

            if($errorDate)
                self::$errors['scheduled_date'] = $errorDate;
        }

        if($_REQUEST['status']) {
            self::validateStatus($_REQUEST['status']);
            $update['status'] = $_REQUEST['status'];
        }

        if(self::$errors)
            exitError(400, self::$errors);

        //Perform update
        try {
            Request::$baseModel->update($update ?? null, ['request_id' => (int) getURIparam(2)]);
            http_response_code(200);
        } catch(\Exception $ex) {
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
        if(!$title || strlen($title) < 3 || strlen($title) > 120)
            self::$errors['title'] = 'Invalid title (Accepted values: 3-120 characters)';
    }

    private static function validateDescription($description) {
        if($description && strlen($description) > 65535)
            self::$errors['description'] = 'Invalid description (Accepted values: 65,535 chars. max';
    }

    private static function validateStatus($status) {
        if($status && !in_array($newStatus, [-1, 0, 1]))
            self::$errors['status'] = 'Invalid status (acceptable values: -1 (rejected), 0 (unevaluated), 1 (accepted)';
    }
}