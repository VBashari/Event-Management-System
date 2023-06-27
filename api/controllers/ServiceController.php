<?php

require_once __DIR__ . '/interfaces/IController.php';
require_once __DIR__ . '/helper_controllers/PhotoController.php';
require_once __DIR__ . '/helper_controllers/TagController.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../utils/utils.php';
require_once __DIR__ . '/../controllers/AuthController.php';

/**
 * Endpoints:
 *      GET POST        services
 *      GET POST        services?limit={}&offset={}
 *      GET             services?q={}
 *      GET             services?q={}&limit={}&offset={}
 *      GET PATCH DEL   services/{id}
 *      GET             services/user/{id}
 *      GET             services/user/{id}?limit={}&offset={}
 */

class ServiceController implements IController {
    private static $photoController;
    private static $errors;
    private static $data;
    private static $files;

    private function __construct() {}

    public static function __constructStatic() {
        self::$photoController = new PhotoController('service', 'service_photo', '/../../../photos/services');
        self::$data = readRequestBody();
        self::$files = self::$data["files"] ?? [];
    }

    /**
     * Get all services (optional pagination)
     */
    public static function getAll($limitQueries = null) {
        try {
            $services = Service::$baseModel->getAll($limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);
            
            //Get accompanying photos & tags for each service post
            for($i = 0; $i < count($services); $i++){
                $photos = self::$photoController->baseModel->getAllBy($services[$i]['service_id']);
                $tags = Tag::getAllBy($services[$i]['service_id']);

                if($photos)
                    $services[$i]['photos'] = $photos;

                if($tags)
                    $services[$i]['tags'] = $tags;
            }

            http_response_code(200);
            return $services;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Get all services by specified user (optional pagination)
     */
    public static function getAllBy($limitQueries = null) {
        try {
            $services = Service::getAllBy((int) getURIparam(3), $limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);

            //Getting photos & tags for each post
            for($i = 0; $i < count($services); $i++) {
                $photos = self::$photoController->baseModel->getAllBy($services[$i]['service_id']);
                $tags = Tag::getAllBy($services[$i]['service_id']);

                if($photos)
                    $services[$i]['photos'] = $photos;
                
                if($tags)
                    $services[$i]['tags'] = $tags;
            }
            
            http_response_code(200);
            return $services;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function getSearch($parameters) {
        try {
            $services = Service::getSearch($parameters['q'], $parameters['limit'] ?? null, $parameters['offset'] ?? null);
            
            //Getting photos & tags for each post
            for($i = 0; $i < count($services); $i++) {
                $photos = self::$photoController->baseModel->getAllBy($services[$i]['service_id']);
                $tags = Tag::getAllBy($services[$i]['service_id']);

                if($photos)
                    $services[$i]['photos'] = $photos;
                
                if($tags)
                    $services[$i]['tags'] = $tags;
            }
            
            http_response_code(200);
            return $services;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Get service with specified id
     */
    public static function get() {
        try {
            $service_id = (int) getURIparam(2);
            $service = Service::get($service_id);
            if ($service === false) {
                exitError(404, "Service with id $service_id does not exist");
            }

            //Get accompanying photos & tags
            $photos = self::$photoController->baseModel->getAllBy($service['service_id']);
            $tags = Tag::getAllBy($service['service_id']);

            if($photos)
                $service['photos'] = $photos;

            if($tags)
                $service['tags'] = $tags;

            http_response_code(200);
            return $service;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     */
    public static function create() {
        AuthController::requireUserType([UserType::ADMIN->value, UserType::EVENT_ORGANIZER->value, UserType::VENDOR->value]);
        self::$errors = [];
        
        //Error checking
        if(!isset(self::$data['servicer_id']))
            self::$errors['servicer_id'] = 'Required value';
        
        self::validateTitle(self::$data['title'] ?? null);
        self::validateDescription(self::$data['description'] ?? null);
        self::validateAvgPrice(self::$data['avg_price'] ?? null);
        self::validateTags(self::$data['tags'] ?? null);
        self::$photoController->validatePhotos(self::$files['photos']['name'] ?? null, self::$data['alt_texts'] ?? null, self::$data['captions'] ?? null);

        if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);

        if(self::$errors)
            exitError(400, self::$errors);
        
        //Service insertion
        try {
            $input = [
                'servicer_id' => self::$data['servicer_id'],
                'title' => self::$data['title'],
                'avg_price' => self::$data['avg_price']
            ];

            if(isset(self::$data['description']))
                $input['description'] = self::$data['description'];

            $service_id = Service::insert($input);
            if ($service_id !== false) {
                $serviceID = Service::$baseModel->db->lastInsertId();
                $photos = self::$files['photos'];

                //Upload photos of the service
                for($i = 0; $i < count($photos['name']); $i++)
                    self::$photoController->uploadPhoto([
                        'service_id' => $serviceID, 
                        'photo_reference' => $photos['full_path'][$i] ?? null, 
                        'alt_text' => $_REQUEST['alt_texts'][$i] ?? null,
                        'caption' => $_REQUEST['captions'][$i] ?? null
                    ], $photos['tmp_name'][$i]);

                //Upload tags, if there are any
                if(isset(self::$data['tags'])) {
                    foreach(self::$data['tags'] as $tag)
                        TagController::insert($serviceID, $tag);
                }

                http_response_code(201);

                return [
                    "error" => 0,
                    "result" => [
                        "id" => $service_id
                    ]
                ];
            } else
                http_response_code(400);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate the specified update data and, if no errors occur, perform update
     */
    public static function update() {
        $serviceID = (int) getURIparam(2);
        $service = Service::get($serviceID);
        if ($service === false) {
            exitError(404, "Service with id $serviceID does not exist");
        }
        $servicer_id = $service['servicer_id'];
        AuthController::requireUser($servicer_id);

        self::$errors = [];

        //Error checking
        if(!self::$data)
            exitError(400, 'Update cannot be empty');

        if(isset(self::$data['title'])) {
            self::validateTitle(self::$data['title']);
            $update['title'] = self::$data['title'];
        }

        if(isset(self::$data['description'])) {
            self::validateDescription(self::$data['description']);
            $update['description'] = self::$data['description'];
        }

        if(isset(self::$data['avg_price'])) {
            self::validateAvgPrice(self::$data['avg_price']);
            $update['avg_price'] = self::$data['avg_price'];
        }

        if(isset(self::$files['photos'])) {
            self::$photoController->validatePhotos(self::$files['photos']['name'], self::$data['alt_texts'], self::$data['captions']);

            if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);
        }
            
        if(isset(self::$data['tags']))
            self::validateTags(self::$data['tags']);

        if(self::$errors)
            exitError(400, self::$errors);

        //Perform update
        if(isset($update)) {
            try {
                Service::$baseModel->update($update, ['service_id' => $serviceID]);
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }

        //Update photos & tags
        if(isset(self::$files['photos']))
            self::$photoController->updatePhotos($serviceID);
        
        if(isset(self::$data['tags']))
            TagController::updateTags($serviceID);
        
        http_response_code(200);
    }

    /**
     * Delete specified service
     */
    public static function delete() {
        $serviceID = (int) getURIparam(2);
        $service = Service::get($serviceID);
        if ($service === false) {
            exitError(404, "Service with id $serviceID does not exist");
        }
        $servicer_id = $service['servicer_id'];
        AuthController::requireUser($servicer_id);

        try {
            Service::$baseModel->delete(['service_id' => $serviceID]);
            http_response_code(204);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    //Validation functions

    /**
     * Check if tags are valid: if not, adds an error to the class errors
     * 
     * @param array $tags
     */
    private static function validateTags($tags) {
        if(!$tags)
            return;

        foreach($tags as $tag) {
            $tagError = TagController::validateTag($tag);

            if($tagError) {
                self::$errors['tags'] = $tagError;
                break;
            }
        }
    }

    /**
     * Check if title is 3-120 characters long: if not, adds an error to the class errors
     * 
     * @param string $title
     */
    private static function validateTitle($title) {
        if(!$title)
            self::$errors['title'] = 'Required value';
        elseif(strlen($title) < 3 || strlen($title) > 120)
            self::$errors['title'] = 'Invalid title (Accepted values: 3-120 characters)';
    }

    /**
     * Check if description exceeds 65535 characters: if so, adds an error to the class errors
     * 
     * @param string $description
     */
    private static function validateDescription($description) {
        if($description && strlen($description) > 65535)
            self::$errors['description'] = 'Invalid description (65,535 chars. max)';
    }

    /**
     * Check if average price is positive: if not, adds an error to the class errors
     * 
     * @param string $avgPrice
     */
    private static function validateAvgPrice($avgPrice) {
        if(!$avgPrice)
            self::$errors['avg_price'] = 'Required value';
        elseif($avgPrice <= 0)
            self::$errors['avg_price'] = 'Invalid price (positive values only)';
    }
}

ServiceController::__constructStatic();