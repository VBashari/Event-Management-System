<?php

require_once __DIR__ . '/../utils/utils.php';

class Connector extends PDO {
    private static $instance;

    public static function getConnector() {        
        if(!isset(self::$instance)) {
            self::$instance = new Connector();
        }
        return self::$instance;
    }

    private final function __construct() {
        loadEnv(__DIR__ . '/../.env');

        $dsn = 'mysql:host=' . getenv('DBHOST') . ';dbname=' . getenv('DBNAME');
        parent::__construct($dsn, getenv('DBUSER'), getenv('DBPASS'));

        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}