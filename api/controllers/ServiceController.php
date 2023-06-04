<?php

require_once __DIR__ . '/interfaces/IController.php';
require_once __DIR__ . '/helper_controllers/PhotoController.php';
require_once __DIR__ . '/helper_controllers/TagController.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../utils/utils.php';

//TODO auth check

/**
 * Endpoints:
 *      GET POST        services
 *      GET POST        services?limit={}&offset={}
 *      GET PATCH DEL   services/{id}
 *      GET             services/user/{id}
 *      GET             services/user/{id}?limit={}&offset={}
 */

class ServiceController implements IController {
    private static $photoController;
    private static $errors;

    private function __construct() {}

    public static function __constructStatic() {
        self::$photoController = new PhotoController('service', 'service_photo', '/../../../photos/services');
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

            //Getting photos for each post
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
            $service = Service::get((int) getURIparam(2));

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
        self::$errors = [];
        
        //Error checking
        if(!isset($_POST['servicer_id']))
            self::$errors['servicer_id'] = 'Required value';
        
        self::validateTitle($_POST['title'] ?? null);
        self::validateDescription($_POST['description'] ?? null);
        self::validateAvgPrice($_POST['avg_price'] ?? null);
        self::validateTags($_POST['tags'] ?? null);
        self::$photoController->validatePhotos($_FILES['photos']['name'] ?? null, $_POST['alt_texts'] ?? null, $_POST['captions'] ?? null);

        if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);

        if(self::$errors)
            exitError(400, self::$errors);
        
        //Service insertion
        try {
            $input = [
                'servicer_id' => $_POST['servicer_id'],
                'title' => $_POST['title'],
                'avg_price' => $_POST['avg_price']
            ];

            if(isset($_POST['description']))
                $input['description'] = $_POST['description'];

            if(Service::insert($input)) {
                $serviceID = Service::$baseModel->db->lastInsertId();
                $photos = $_FILES['photos'];

                //Upload photos of the service
                for($i = 0; $i < count($photos['name']); $i++)
                    self::$photoController->uploadPhoto([
                        'service_id' => $serviceID, 
                        'photo_reference' => $photos['full_path'][$i] ?? null, 
                        'alt_text' => $_REQUEST['alt_texts'][$i] ?? null,
                        'caption' => $_REQUEST['captions'][$i] ?? null
                    ], $photos['tmp_name'][$i]);

                //Upload tags, if there are any
                if(isset($_POST['tags'])) {
                    foreach($_POST['tags'] as $tag)
                        TagController::insert($serviceID, $tag);
                }

                http_response_code(201);
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
        self::$errors = [];
        //TODO fix for getting images
        $data = json_decode(file_get_contents('php://input'), true);

        //Error checking
        if(!$data)
            exitError(400, 'Update cannot be empty');

        if(isset($data['title'])) {
            self::validateTitle($data['title']);
            $update['title'] = $data['title'];
        }

        if(isset($data['description'])) {
            self::validateDescription($data['description']);
            $update['description'] = $data['description'];
        }

        if(isset($data['avg_price'])) {
            self::validateAvgPrice($data['avg_price']);
            $update['avg_price'] = $data['avg_price'];
        }

        if(isset($_FILES['photos'])) {
            self::$photoController->validatePhotos($_FILES['photos']['name'], $data['alt_texts'], $data['captions']);

            if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);
        }
            
        if(isset($data['tags']))
            self::validateTags($data['tags']);

        if(self::$errors)
            exitError(400, self::$errors);


        $serviceID = (int) getURIparam(2);

        //Perform update
        if(isset($update)) {
            try {
                Service::$baseModel->update($update, ['service_id' => $serviceID]);
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }

        //Update photos & tags
        if(isset($_FILES['photos']))
            self::$photoController->updatePhotos($serviceID);
        
        if(isset($data['tags']))
            TagController::updateTags($serviceID);
        
        http_response_code(200);
    }

    /**
     * Delete specified service
     */
    public static function delete() {
        try {
            Service::$baseModel->delete(['service_id' => (int) getURIparam(2)]);
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