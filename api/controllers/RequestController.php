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
    private static $data;
    
    private function __construct() {}

    public static function __constructStatic() {
        self::$data = readRequestBody();
    }
    
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
            $request_id = (int) getURIparam(2);
            $request = Request::get($request_id);
            if ($request === false) {
                exitError(404, "Request with id $request_id does not exist");
            }

            http_response_code(200);
            return $request;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
        self::$errors = [];

        if(!isset(self::$data['requester_id']))
            self::$errors['requester_id'] = 'Required value';
        
        if(!isset(self::$data['servicer_id']))
            self::$errors['servicer_id'] = 'Required value';

        self::validateTitle(self::$data['title'] ?? null);
        self::validateDescription(self::$data['description'] ?? null);
        self::validateStatus(self::$data['status'] ?? null);
        $errorDate = validateDate(self::$data['scheduled_date'] ?? null);
        
        if($errorDate)
            self::$errors['scheduled_date'] = $errorDate;
        
        if(self::$errors)
            exitError(400, self::$errors);

        try {
            $input = [
                'requester_id' => self::$data['requester_id'],
                'servicer_id' => self::$data['servicer_id'],
                'title' => self::$data['title'],
                'scheduled_date' => self::$data['scheduled_date']
            ];

            if(isset(self::$data['description']))
                $input['description'] = self::$data['description'];

            if(isset(self::$data['status']))
                $input['status'] = self::$data['status'];

            $request_id = Request::$baseModel->insertUserCheck($input, 'servicer_id');
            if ($request_id !== false) {
                http_response_code(201);

                return [
                    "error" => 0,
                    "result" => [
                        "id" => $request_id
                    ]
                ];
            }
            else
                http_response_code(400);
            
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

        //Data validation
        if(isset(self::$data['title'])) {
            self::validateTitle(self::$data['title']);
            $update['title'] = self::$data['title'];
        }

        if(isset(self::$data['description'])) {
            self::validateDescription(self::$data['description']);
            $update['description'] = self::$data['description'];
        }

        if(isset(self::$data['scheduled_date'])) {
            $errorDate = validateDate(self::$data['scheduled_date']);
            $update['scheduled_date'] = self::$data['scheduled_date'];

            if($errorDate)
                self::$errors['scheduled_date'] = $errorDate;
        }

        if(isset(self::$data['status'])) {
            self::validateStatus(self::$data['status']);
            $update['status'] = self::$data['status'];
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

RequestController::__constructStatic();
