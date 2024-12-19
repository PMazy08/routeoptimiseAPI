<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use Kreait\Firebase\Factory;
use App\Middleware\FirebaseAuthMiddleware; // เพิ่มการนำเข้า Middleware

// โหลด .env จากโฟลเดอร์รากของโปรเจกต์
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// สร้างแอป
$app = AppFactory::create();

// โหลด Routes
(require __DIR__ . '/../app/Routes/routes.php')($app);

// รันแอป
$app->run();






