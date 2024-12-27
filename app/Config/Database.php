<?php

// namespace App\Config;

// use PDO;
// use Exception;

// class Database {
//     private static $instance = null;

//     private function __construct() {}
//     private function __clone() {}

//     public static function connect() {
//         if (self::$instance === null) {
//             $host = $_ENV['DB_HOST'];
//             $dbname = $_ENV['DB_NAME'];
//             $user = $_ENV['DB_USER'];
//             $pass = $_ENV['DB_PASS'];
//             $charset = $_ENV['DB_CHARSET'];

//             $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
//             try {
//                 self::$instance = new PDO($dsn, $user, $pass, [
//                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//                 ]);
//             } catch (Exception $e) {
//                 die("Could not connect to the database. Error: " . $e->getMessage());
//             }
//         }
//         return self::$instance;
//     }
// }


namespace App\Config;

use PDO;
use Exception;

class Database {
    private static $instance = null; // เก็บการเชื่อมต่อแบบ Singleton

    private function __construct() {} // ป้องกันการสร้าง Instance ใหม่
    private function __clone() {}     // ป้องกันการ Clone

    public static function connect() {
        if (self::$instance === null) {
            // var_dump("Connecting to database...");
            // ดึงค่าการตั้งค่าฐานข้อมูล
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $charset = $_ENV['DB_CHARSET'];

            // สร้าง Data Source Name (DSN)
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

            try {
                // สร้างการเชื่อมต่อ PDO
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (Exception $e) {
                // จัดการข้อผิดพลาด
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }else{
            // var_dump("Reusing existing connection.");
        }

        return self::$instance; // คืนค่าการเชื่อมต่อเดิม
    }
}
