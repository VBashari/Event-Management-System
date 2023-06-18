<?php

require_once __DIR__ . '/../../models/Photo.php';
require_once __DIR__ . '/../../utils/utils.php';

class PhotoController {
    public $baseModel;
    private $typeIDField;
    public $errors;

<<<<<<< HEAD
    public function __construct($typeIDField, $tableName) {
        $this->baseModel = new Photo($typeIDField, $tableName);
        $this->typeIDField = $typeIDField;
=======
    private $folderPath;
    private static $acceptedImageTypes = ['png', 'jpg', 'jpeg'];

    public function __construct($typeIDField, $tableName, $folderPath) {
        $this->baseModel = new Photo($typeIDField, $tableName);
        $this->typeIDField = $typeIDField;
        $this->folderPath = $folderPath;
>>>>>>> api_rewrite
    }

    /**
     * Insert new photo for a specified post
     * 
     * @param array $parameters: key-value input of post photo columns
     */
    public function uploadPhoto(array $parameters, $tmpName) {
        //Create unique file name
<<<<<<< HEAD
        $fileName = uniqid($parameters['photo_reference']);

        if(strlen($fileName) > 255)
            $fileName = substr($fileName, 0, 255);

        try { 
            self::$baseModel->insert([
                $this->typeIDField => $parameters[$this->typeIDField],
                'photo_reference' => $parameters['photo_reference'],
=======
        $fileName = uniqid() . $parameters['photo_reference'];

        if(strlen($fileName) > 255)
            $fileName = substr($fileName, 255);

        try { 
            $this->baseModel->insert([
                $this->typeIDField . '_id' => $parameters[$this->typeIDField . '_id'],
                'photo_reference' => $fileName,
>>>>>>> api_rewrite
                'alt_text' => $parameters['alt_text'] ?? null,
                'caption' => $parameters['caption'] ?? null
            ]);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }

<<<<<<< HEAD
        move_uploaded_file($tmpName, $postPhotosPath . '/' . $parameters['photo_reference']);
=======
        move_uploaded_file($tmpName, __DIR__ . $this->folderPath . '/' . $fileName);
>>>>>>> api_rewrite
    }

    /**
     * Updates uploaded photos of the specified record by comparing the current photo files 
     * against the inserted ones (both new, if inserted, and existing ones):
     * if a current photo file is not in the inserted group, it's assumed it's been deleted
     * 
     * @param integer $recordID
     */
    public function updatePhotos($recordID) {
        $photos = $_FILES['photos'];

<<<<<<< HEAD
        $existingPhotos = self::$baseModel->getAllReferencesBy($recordID);
=======
        $existingPhotos = $this->baseModel->getAllReferencesBy($recordID);
>>>>>>> api_rewrite
        //Get missing/deleted photos
        $deletedPhotos = array_diff($existingPhotos, $photos['name']);

        foreach($deletedPhotos as $photoPath)
<<<<<<< HEAD
            self::$baseModel->delete($photoPath);
=======
            $this->baseModel->delete($photoPath);
>>>>>>> api_rewrite
        
        //Get new inserted photos
        $newPhotos = array_diff($photos['name'], $existingPhotos);

        foreach($newPhotos as $photoPath) {
            $photoKey = array_search($photoPath, $photos['full_path']);
            
            $this->uploadPhoto([
<<<<<<< HEAD
                $this->typeIDField => $recordID, 
=======
                $this->typeIDField . '_id' => $recordID, 
>>>>>>> api_rewrite
                'photo_reference' => $photoPath, 
                'alt_text' => $_REQUEST['alt_text'][$photoKey],
                'caption' => $_REQUEST['caption'][$photoKey],
            ], $photos['tmp_name'][$photoKey]);
        }
    }

    //Validation functions

    /**
     * Validate photo file and its accompanying alt. text & caption
     * 
     * @param array $photoParams: photo name, alt. text, caption
     */
    public function validatePhoto($photoParams) {
        $this->errors = [];

        $this->validatePhotoType($photoParams['name']);
<<<<<<< HEAD
        $this->validateAltText($photoParams['alt_text']);
        $this->validateCaption($photoParams['caption']);
=======
        $this->validateAltText($photoParams['alt_text'] ?? null);
        $this->validateCaption($photoParams['caption'] ?? null);
>>>>>>> api_rewrite
    }

    /**
     * Check if each images' format is accepted, and if the accompanying alt. 
     * texts & captions conform too: if not, adds an error to the class errors
     * 
     * @param array $photoNames
     * @param array $altTexts
     * @param array $captions
     */
<<<<<<< HEAD
    public static function validatePhotos($photoNames, $altTexts, $captions) {
        for($i = 0; $i < count($photoNames); $i++) {
            self::validatePhoto([
                'name' => $photoNames[$i],
                'alt_text' => $altTexts[$i],
                'caption' => $captions[$i]
            ]);

            if(self::$photoController->errors)
=======
    public function validatePhotos($photoNames, $altTexts, $captions) {
        if(!$photoNames) {
            $this->errors['photos'] = 'At least one photo is required';
            return;
        }

        for($i = 0; $i < count($photoNames); $i++) {
            $input['name'] = $photoNames[$i];

            if(isset($altTexts[$i]))
                $input['alt_text'] = $altTexts[$i];
            
            if(isset($captions[$i]))
                $input['caption'] = $captions[$i];

            $this->validatePhoto($input);

            if($this->errors)
>>>>>>> api_rewrite
                break;
        }
    }

    /**
     * Check if photo file is of a supported type (PNG, JPG, JPEG): if not, adds an error to the class errors
     * 
     * @param array $photoName
     */
    private function validatePhotoType($photoName) {
        //Check for image type
<<<<<<< HEAD
        if(!in_array(pathinfo($photoName, PATHINFO_EXTENSION), $acceptedImageTypes))
=======
        if(!in_array(pathinfo($photoName, PATHINFO_EXTENSION), self::$acceptedImageTypes))
>>>>>>> api_rewrite
            $this->errors['photos'] = 'Invalid photo type (Accepted types: PNG, JPG/JPEG';
    }

    /**
     * Check if alt. text is at most 255 characters: if not, adds an error to the class errors
     * 
     * @param array $altText
     */
    private function validateAltText($altText) {
<<<<<<< HEAD
        if(strlen($altText) > 255)
=======
        if($altText && strlen($altText) > 255)
>>>>>>> api_rewrite
            $this->errors['photos'] = 'Invalid alt. text (Accepted values: 255 characters max.';
    }

    /**
     * Check if caption is at most 120 characters: if not, adds an error to the class errors
     * 
     * @param array $caption
     */
    private function validateCaption($caption) {
<<<<<<< HEAD
        if(strlen($caption) > 120)
=======
        if($caption && strlen($caption) > 120)
>>>>>>> api_rewrite
            $this->errors['photos'] = 'Invalid caption (Accepted values: 120 characters max.';
    }
}