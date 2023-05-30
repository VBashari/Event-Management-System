<?php

require_once __DIR__ . '/ReadOnlyController.php';

interface GenericController extends ReadOnlyController {
    public static function create();
    public static function update();
    public static function delete();
}