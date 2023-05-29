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

    private function __construct() {}

    public static function __constructStatic() {
        self::$photoController = new PhotoController('post', 'post_photo');
    }

    public static function get() {
        try {
            $post = Post::get((int) getURIparam(2));
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
        $photos = $_FILES['photos'];
        self::validateTitle($_POST['title']);
        self::$photoController->validatePhotos($_FILES['photos']['name'], $_POST['alt_texts'], $_POST['captions']);

        if(self::$photoController->errors)
            self::$errors = array_merge(self::$errors, self::$photoController->errors);

        if(self::$errors)
            return false;

        try {
            if(Post::insert(['servicer_id' => $_POST['servicer_id'], 'title' => $_POST['title']])) {
                $postID = Post::$baseModel->db->lastInsertID();

                for($i = 0; $i < count($photos['name']); $i++)
                    self::$photoController->uploadPhoto([
                        'post_id' => $postID, 
                        'photo_reference' => $photos['full_path'][$i], 
                        'alt_text' => $_REQUEST['alt_text'][$i],
                        'caption' => $_REQUEST['caption'][$i]
                    ], $photos['tmp_name'][$i]);
                
                http_response_code(201);
            }

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
        if($_REQUEST['title']) {
            $update['title'] = $_REQUEST['title'];
            self::validateTitle($update['title']);
        }

        if($_FILES['photos']) {
            self::$photoController->validatePhotos($_FILES['photos']['name'], $_POST['alt_texts'], $_POST['captions']);

            if(self::$photoController->errors)
                self::$errors = array_merge(self::$errors, self::$photoController->errors);
        } else
            self::$errors['photos'] = 'Photos are required for posts';

        if(self::$errors)
            return false;

        $postID = (int) getURIparam(2);

        if(isset($update)) {
            try {
                Post::$baseModel->update($update, ['post_id' => $postID]);
            } catch(\Exception $ex) {
                exitError(400, $ex->getMessage());
            }
        }

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
        if(!$title || strlen($title) < 3 || strlen($title) > 120)
            self::$errors['title'] = 'Invalid title (Accepted values: 3-120 characters)';
    }
}

PostController::__constructStatic();