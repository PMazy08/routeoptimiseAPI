<?php

namespace App\Config;

use PDO;
use Exception;

class Database {
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function connect() {
        if (self::$instance === null) {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $charset = $_ENV['DB_CHARSET'];

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (Exception $e) {
                die("Could not connect to the database. Error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
