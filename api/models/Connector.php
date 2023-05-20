<?php

class Connector extends PDO {
    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=eventmanagementdb';
        $user = 'root';
        $password = 'password';

        parent::__construct($dsn, $user, $password);

        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}