<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use App\Models\User;

class FirebaseAuthMiddleware
{
    private $auth;

    public function __construct()
    {   
        // โหลด Firebase Credentials
        $this->auth = (new Factory)
            ->withServiceAccount($_ENV['FIREBASE_CREDENTIALS']) 
            ->createAuth();
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        // ดึง Token จาก Header Authorization
        $header = $request->getHeaderLine('Authorization');

        // ตรวจสอบว่า Token มีหรือไม่
        if (empty($header)) {
            return $this->respondWithError('Unauthorized: Missing Authorization header');
        }

        // ตรวจสอบว่า Authorization header มีรูปแบบ Bearer <token> หรือไม่
        if (!preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $this->respondWithError('Unauthorized: Invalid token format');
        }

        // เอา Token ออกจาก matches ที่ได้
        $tokenString = $matches[1];

        try {
            // ตรวจสอบ Token และดึงข้อมูล
            $verifiedIdToken = $this->auth->verifyIdToken($tokenString);
            $uid = $verifiedIdToken->claims()->get('sub');
            $email = $verifiedIdToken->claims()->get('email');

            // เพิ่ม UID และ email เข้าไปใน request attribute
            $request = $request->withAttribute('uid', $uid)
                               ->withAttribute('email', $email);

            // เช็ค ว่ามี user มั้ย
            $user = User::getByUID($uid);
            // var_dump($user);
            if (!$user) {  
                User::createFromFirebase($request);  // เรียกใช้ createFromFirebase() เพื่อสร้างผู้ใช้
            }

            // ส่ง request ต่อไปยัง handler ตัวถัดไป
            return $handler->handle($request);

        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    // ฟังก์ชันตอบกลับข้อผิดพลาด
    private function respondWithError($message): Response
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withStatus(401)
                        ->withHeader('Content-Type', 'application/json');
    }
}
