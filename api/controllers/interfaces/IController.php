<?php

require_once __DIR__ . '/GenericController.php';

interface IController extends GenericController {
    public static function getAllBy($limitQueries = null);
}