<?php

class Connector extends PDO {
    private static $instance;

    public static function getConnector() {        
        if(!isset(self::$instance)) {
            self::$instance = new Connector();
        }
        return self::$instance;
    }

    private final function __construct() {
        // if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        //     $dsn = 'mysql:host=localhost;dbname=eventmanagementdb';
        //     $user = 'root';
        //     $password = 'password';
        // }
        // else {
        //     $dsn = 'mysql:host=mysql-event-management.alwaysdata.net;dbname=event-management_db';
        //     $user = '313822';
        //     $password = 'Event1234.';
        // }

        $dsn = 'mysql:host=localhost;dbname=eventmanagementdb';
        $user = 'root';
        $password = 'password';
        parent::__construct($dsn, $user, $password);

        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}