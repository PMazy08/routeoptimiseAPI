<?php

namespace App\Controllers;

use App\Models\Trip;
use App\Helpers\UserHelper;

class TripController{
    // GET All BY USER_ID
    public function getAllByUserId($request, $response){
        $user = UserHelper::getUserFromRequest($request); 
        $result = Trip::getAllByUserId($user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Create Trips
    public function create($request, $response){
        try {
            $data = json_decode($request->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON format');
            }

            // ดึงข้อมูล user จาก request
            $user = UserHelper::getUserFromRequest($request); 

            // บันทึกข้อมูลลงในโมเดล Student
            $result = Trip::create($data, $user['id']);

            // // ส่งผลลัพธ์กลับไปใน response
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

}