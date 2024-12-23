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


// Add CORS Middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

// Handle OPTIONS requests
$app->options('/{routes:.+}', function ($request, $response) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization');
});

// โหลด Routes
(require __DIR__ . '/../app/Routes/routes.php')($app);

// รันแอป
$app->run();






