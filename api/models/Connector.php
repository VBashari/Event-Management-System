<?php

$config = include __DIR__ . '/../utils/config.php';

class Connector extends PDO {
    private static $instance;

    public static function getConnector() {        
        if(!isset(self::$instance)) {
            self::$instance = new Connector();
        }
        return self::$instance;
    }

    private final function __construct() {
        global $config;
        
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['name'];
        parent::__construct($dsn, $config['user'], $config['password']);

        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}