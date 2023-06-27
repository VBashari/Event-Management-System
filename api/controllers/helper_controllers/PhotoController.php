<?php

require_once __DIR__ . '/../../models/Photo.php';
require_once __DIR__ . '/../../utils/utils.php';

class PhotoController {
    public $baseModel;
    private $typeIDField;
    public $errors;

    private $folderPath;
    private static $acceptedImageTypes = ['png', 'jpg', 'jpeg'];

    public function __construct($typeIDField, $tableName, $folderPath, $data = [], $files = []) {
        $this->baseModel = new Photo($typeIDField, $tableName);
        $this->typeIDField = $typeIDField;
        $this->folderPath = $folderPath;
        $this->data = $data;
        $this->files = $files;
    }

    /**
     * Insert new photo for a specified post
     * 
     * @param array $parameters: key-value input of post photo columns
     */
    public function uploadPhoto(array $parameters, $tmpName) {
        //Create unique file name
        $fileName = uniqid() . $parameters['photo_reference'];

        if(strlen($fileName) > 255)
            $fileName = substr($fileName, 255);

        try { 
            $this->baseModel->insert([
                $this->typeIDField . '_id' => $parameters[$this->typeIDField . '_id'],
                'photo_reference' => $fileName,
                'alt_text' => $parameters['alt_text'] ?? null,
                'caption' => $parameters['caption'] ?? null
            ]);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
        
        if (!file_exists($tmpName)) {
            exitError(400, "File $tmpName does not exist");
        }

        if (!preg_match('/^[a-zA-Z0-9-_\.]+$/', $fileName)) {
            exitError(400, "Invalid file name $fileName");
        }

        if (strpos($fileName, '..') !== false) {
            exitError(400, "Invalid file name $fileName");
        }

        $full_path = __DIR__ . $this->folderPath . '/' . $fileName;

        rename($tmpName, $full_path);
    }

    /**
     * Updates uploaded photos of the specified record by comparing the current photo files 
     * against the inserted ones (both new, if inserted, and existing ones):
     * if a current photo file is not in the inserted group, it's assumed it's been deleted
     * 
     * @param integer $recordID
     */
    public function updatePhotos($recordID) {
        $photos = $this->files['photos'];

        $existingPhotos = $this->baseModel->getAllReferencesBy($recordID);
        //Get missing/deleted photos
        $deletedPhotos = array_diff($existingPhotos, $photos['name']);

        foreach($deletedPhotos as $photoPath)
            $this->baseModel->delete($photoPath);
        
        //Get new inserted photos
        $newPhotos = array_diff($photos['name'], $existingPhotos);

        foreach($newPhotos as $photoPath) {
            $photoKey = array_search($photoPath, $photos['full_path']);
            
            $this->uploadPhoto([
                $this->typeIDField . '_id' => $recordID, 
                'photo_reference' => $photoPath, 
                'alt_text' => $this->data['alt_text'][$photoKey] ?? null,
                'caption' => $this->data['caption'][$photoKey] ?? null,
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
        $this->validateAltText($photoParams['alt_text'] ?? null);
        $this->validateCaption($photoParams['caption'] ?? null);
    }

    /**
     * Check if each images' format is accepted, and if the accompanying alt. 
     * texts & captions conform too: if not, adds an error to the class errors
     * 
     * @param array $photoNames
     * @param array $altTexts
     * @param array $captions
     */
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
        if(!in_array(strtolower(pathinfo($photoName, PATHINFO_EXTENSION)), self::$acceptedImageTypes))
            $this->errors['photos'] = 'Invalid photo type (Accepted types: PNG, JPG/JPEG';
    }

    /**
     * Check if alt. text is at most 255 characters: if not, adds an error to the class errors
     * 
     * @param array $altText
     */
    private function validateAltText($altText) {
        if($altText && strlen($altText) > 255)
            $this->errors['photos'] = 'Invalid alt. text (Accepted values: 255 characters max.';
    }

    /**
     * Check if caption is at most 120 characters: if not, adds an error to the class errors
     * 
     * @param array $caption
     */
    private function validateCaption($caption) {
        if($caption && strlen($caption) > 120)
            $this->errors['photos'] = 'Invalid caption (Accepted values: 120 characters max.';
    }
}