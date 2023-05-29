<?php

require_once __DIR__ . '/../../models/Tag.php';

class TagController {
    /**
     * Insert tag for specified service
     * 
     * @param integer $servicerID
     * @param string $tag
     */
    public static function insert($serviceID, $tag) {
        try {
            return Tag::$baseModel->insert(['service_id' => $serviceID, 'tag' => $tag]);
        } catch(\Exception $ex) {
            exitError(400, $ex->getMessage());
        }
    }

    /**
     * Updates uploaded tags of the specified service by comparing the current tags
     * against the inserted ones (both new, if inserted, and existing ones):
     * if a tag is not in the inserted group, it's assumed it's been deleted
     * 
     * @param integer $recordID
     */
    public static function updateTags($serviceID) {
        $tags = $_REQUEST['tags'];

        $existingTags = Tag::getAllBy($serviceID);
        $deletedTags = array_diff($existingTags, $tags); //Get missing/deleted tags

        foreach($deletedTags as $tag)
            self::$baseModel->delete([$serviceID, $tag]);
        
        $newTags = array_diff($tags, $existingTags); //Get new inserted tags

        foreach($newTags as $tag)
            self::insert([$serviceID, $tag]);
    }

    /**
     * Check if tag is 3-25 chars. long: if not, adds an error to the class errors
     * 
     * @param string $tag
     */
    public static function validateTag($tag) {
        if(!$tag || strlen($tag) < 3 || strlen($tag) > 25)
            return 'Invalid tag (25 chars. max)';
    }
}