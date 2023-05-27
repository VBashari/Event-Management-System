<?php

class Database {
    public function __construct(
        private $host,
        private $name,
        private $user,
        private $password
    ) {
    }

    public function getConnection() {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }
}