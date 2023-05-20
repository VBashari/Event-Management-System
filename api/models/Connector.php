<?php

class Connector extends PDO {
    private final function __construct() {
        if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
            $dsn = 'mysql:host=localhost;dbname=eventmanagementdb';
            $user = 'root';
            $password = 'password';
        }
        else {
            $dsn = 'mysql:host=mysql-event-management.alwaysdata.net;dbname=event-management_db';
            $user = '313822';
            $password = 'Event1234.';
        }

        parent::__construct($dsn, $user, $password);

        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    // there's no point in creating multiple instances of the same connection
    // make it a singleton
    private static $obj;
    public static function getConnector() {
        if(!isset(self::$obj)) {
            self::$obj = new Connector();
        }
        return self::$obj;
    }
}