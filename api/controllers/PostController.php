<?php

require_once __DIR__ . '/interfaces/IController.php';
require_once __DIR__ . '/helper_controllers/PhotoController.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../utils/utils.php';

//TODO auth check

/**
 * Endpoints:
 *      GET POST        posts
 *      GET POST        posts?limit={}&offset={}
 *      GET PATCH DEL   posts/{id}
 *      GET             posts/user/{id}
 *      GET             posts/user/{id}?limit={}&offset={}
 */

class PostController implements IController {
    private static $photoController;
    private static $errors;
    private static $data;

    private function __construct() {}

    public static function __constructStatic() {
        self::$photoController = new PhotoController('post', 'post_photo', '/../../../photos/posts');
        self::$data = readRequestBody();
    }

    public static function get() {
        try {
            $post_id = (int) getURIparam(2);
            $post = Post::get($post_id);
            if ($post === false) {
                exitError(404, "Post with id $post_id does not exist");
            }
            
            $photos = self::$photoController->baseModel->getAllBy($post['post_id']);

            if($photos)
                $post['photos'] = $photos;

            http_response_code(200);
            return $post;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function getAll($limitQueries = null) {
        try {
            $posts = Post::$baseModel->getAll($limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);

            for($i = 0; $i < count($posts); $i++){
                $photos = self::$photoController->baseModel->getAllBy($posts[$i]['post_id']);

                if($photos)
                    $posts[$i]['photos'] = $photos;
            }

            http_response_code(200);
            return $posts;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    public static function getAllBy($limitQueries = null) {
        try {
            $posts = Post::getAllBy((int) getURIparam(3), $limitQueries['limit'] ?? null, $limitQueries['offset'] ?? null);

            //Getting photos for each post
            for($i = 0; $i < count($posts); $i++) {
                $photos = self::$photoController->baseModel->getAllBy($posts[$i]['post_id']);

                if($photos)
                    $posts[$i]['photos'] = $photos;
            }
            
            http_response_code(200);
            return $posts;
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate input data and, if no errors occur, perform insertion
     * 
     * @return boolean
     */
    public static function create() {
        self::$errors = [];

        if(!isset(self::$data['servicer_id']))
            self::$errors['servicer_id'] = 'Required value';
            
        self::validateTitle(self::$data['title'] ?? null);
        self::$photoController->validatePhotos($_FILES['photos']['name'] ?? null, self::$data['alt_texts'] ?? null, self::$data['captions'] ?? null);

        if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);

        if(self::$errors)
           exitError(400, self::$errors);

        try {
            if(Post::$baseModel->insertUserCheck(['servicer_id' => self::$data['servicer_id'], 'title' => self::$data['title']], 'servicer_id')) {
                $postID = Post::$baseModel->db->lastInsertID();
                $photos = $_FILES['photos'];

                for($i = 0; $i < count($photos['name']); $i++)
                    self::$photoController->uploadPhoto([
                        'post_id' => $postID, 
                        'photo_reference' => $photos['full_path'][$i], 
                        'alt_text' => self::$data['alt_texts'][$i] ?? null,
                        'caption' => self::$data['captions'][$i] ?? null
                    ], $photos['tmp_name'][$i]);
                
                http_response_code(201);
            }else
                exitError(400, 'The post couldn\'t be uploaded');
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Validate the specified update data and, if no errors occur, perform update
     * 
     * @param integer $postID
     * @return boolean
     */
    public static function update() {
        self::$errors = [];
        //TODO fix for getting images

        if(isset(self::$data['title'])) {
            $update['title'] = self::$data['title'];
            self::validateTitle($update['title']);
        }

        if(isset($_FILES['photos'])) {
            self::$photoController->validatePhotos($_FILES['photos']['name'], self::$data['alt_texts'], self::$data['captions']);

            if(self::$photoController->errors)
                self::$errors = array_merge(self::$errors, self::$photoController->errors);
        }

        if(self::$errors)
            exitError(400, self::$errors);

        $postID = (int) getURIparam(2);

        if(isset($update)) {
            try {
                Post::$baseModel->update($update, ['post_id' => $postID]);
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }

        if(isset($_FILES['photos']))
            self::$photoController->updatePhotos($postID);
        
        http_response_code(200);
    }

    public static function delete() {
        try {
            Post::$baseModel->delete(['post_id' => (int) getURIparam(2)]);
            http_response_code(204);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    //Auxiliary functions

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
}

PostController::__constructStatic();