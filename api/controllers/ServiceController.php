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
        self::$photoController = new PhotoController('service', 'service_photo');
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
        self::validateTitle($_POST['title']);
        self::validateDescription($_POST['description']);
        self::validateAvgPrice($_POST['avg_price']);
        self::validateTags($_POST['tags']);
        self::$photoController->validatePhotos($_FILES['photos']['name'], $_POST['alt_texts'], $_POST['captions']);

        if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);

        if(self::$errors)
            exitError(400, self::$errors);
        
        //Service insertion
        try {
            $input = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'avg_price' => $_POST['avg_price']
            ];

            if(Service::$baseModel->insert($input)) {
                $serviceID = Service::$baseModel->db->lastInsertId();
                $photos = $_FILES['photos'];

                //Upload photos & tags of the service
                for($i = 0; $i < count($photos['name']); $i++)
                    self::$photoController->uploadPhoto([
                        'post_id' => $postId, 
                        'photo_reference' => $photos['full_path'][$i], 
                        'alt_text' => $_REQUEST['alt_text'][$i],
                        'caption' => $_REQUEST['caption'][$i]
                    ], $photos['tmp_name'][$i]);

                foreach($_POST['tags'] as $tag)
                    TagController::insert($serviceID, $tag);
                
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

        //Error checking
        if($_REQUEST['title']) {
            self::validateTitle($_REQUEST['title']);
            $update['title'] = $_REQUEST['title'];
        }

        if($_REQUEST['description']) {
            self::validateDescription($_REQUEST['description']);
            $update['description'] = $_REQUEST['description'];
        }

        if($_REQUEST['avg_price']) {
            self::validateAvgPrice($_REQUEST['avg_price']);
            $update['avg_price'] = $_REQUEST['avg_price'];
        }

        if($_FILES['photos']) {
            self::$photoController->validatePhotos($_FILES['photos']['name'], $_REQUEST['alt_texts'], $_REQUEST['captions']);

            if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);
        }
            
        if($_REQUEST['tags'])
            self::validateTags($_REQUEST['tags']);

        if(self::$errors)
            exitError(400, self::$errors);

        //Perform update
        if(isset($update)) {
            try {
                //Get ID from uri
                $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $pathParts = array_slice(explode('/', $path), 2);
                $serviceID = $pathParts[2];

                Service::$baseModel->update($update, ['service_id' => $serviceID]);
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }

        //Update photos & tags
        if($_FILES['photos'])
            self::$photoController->updatePhotos($serviceID);
        
        if($_REQUEST['tags'])
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
        if(!$title || strlen($title) < 3 || strlen($title) > 120)
            return false; self::$errors['title'] = 'Invalid title (Accepted values: 3-120 characters)';
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
        if(!avgPrice || avgPrice <= 0)
            self::$errors['avg_price'] = 'Invalid price (positive values only)';
    }
}

ServiceController::__constructStatic();