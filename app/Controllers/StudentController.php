<?php

namespace App\Controllers;

use App\Models\Student;
use App\Helpers\UserHelper;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

 
class StudentController{
    // GET ALL
    public function getAll($request, $response, $args){
        $user = UserHelper::getUserFromRequest($request);
        $result = Student::getAll($user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // GET BY ID
    public function getById($request, $response, $args){
        $id = $args['id'];
        $user = UserHelper::getUserFromRequest($request); 
        $result = Student::getById($id, $user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }


    // GET BY Lng Lat
    public function getByLngLat($request, $response, $args){
        $lng= $args['lng'];
        $lat = $args['lat'];
        $user = UserHelper::getUserFromRequest($request); 
        $result = Student::getByLngLat($lng, $lat, $user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }


    // GET All BY Status
    public function getAllByStatus($request, $response, $args){
        $status = $args['status'] === 'true' ? 1 : ($args['status'] === 'false' ? 0 : null);
        if (is_null($status)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid status']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        $user = UserHelper::getUserFromRequest($request); 
        $result = Student::getAllByStatus($status, $user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // // GET 10 BY Page
    public function getPaginatedStudents($request, $response, $args){
        $page = isset($args['page']) ? (int)$args['page'] : 1;
        $perPage = 10; // จำนวนข้อมูลที่จะแสดงต่อหน้า
        $user = UserHelper::getUserFromRequest($request); 
        $students = Student::getAllPaginated($page, $perPage, $user['id']);
        $response->getBody()->write(json_encode($students));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // // CREATE
    // public function create(Request $request, Response $response, array $args): Response{
    //     try {
    //         // $rawData = file_get_contents("php://input");
    //         // $data = json_decode($rawData, true);

    //         $data = json_decode($request->getBody()->getContents(), true);
    //         $user = UserHelper::getUserFromRequest($request); 
    //         $result = Student::create($data, $user['id']);
    //         $response->getBody()->write(json_encode($result));
    //         return $response->withHeader('Content-Type', 'application/json');
    //     } catch (\Exception $e) {
    //         $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    //         return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    //     }
    // }

    public function create(Request $request, Response $response, array $args): Response
    {
        try {
            // อ่าน JSON data จาก body ของ request
            $data = json_decode($request->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON format');
            }

            // ดึงข้อมูล user จาก request
            $user = UserHelper::getUserFromRequest($request); 

            // บันทึกข้อมูลลงในโมเดล Student
            $result = Student::create($data, $user['id']);

            // ส่งผลลัพธ์กลับไปใน response
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }


    // UPDATE FULL json
    public function updateFull(Request $request, Response $response, array $args): Response{
        try {
            $id = $args['id'];

            $rawData = file_get_contents("php://input");
            // $data = json_decode($request->getBody()->getContents(), true);

            $data = json_decode($rawData, true);
            $user = UserHelper::getUserFromRequest($request); 
            $result = Student::updateAll($id, $data, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // UPDATE STATUS
    public function updateStatus(Request $request, Response $response, array $args): Response{
        try {
            $id = $args['id'];
            // $rawData = file_get_contents("php://input");
            // $data = json_decode($rawData, true);
            
            $data = json_decode($request->getBody()->getContents(), true);
            $user = UserHelper::getUserFromRequest($request); 
            $result = Student::updateStatus($id, $data, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // DELETE ID
    public function delete($request, $response, $args){
        try {
            $id = $args['id'];
            $user = UserHelper::getUserFromRequest($request); 
            $result = Student::delete($id, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // DELETE ALL
    public function deleteAll($request, $response, $args){
        try {
            $user = UserHelper::getUserFromRequest($request); 
            $result = Student::deleteAll($user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // SEARCH
    // public function search($request, $response, $args){
    //     $params = $request->getQueryParams();
    //     if (!isset($params['find']) || empty($params['find'])) {
    //         $response->getBody()->write(json_encode(['message' => 'Please provide a name to search.']));
    //         return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    //     }
    //     $find = $params['find'];
    //     $user = UserHelper::getUserFromRequest($request);
    //     $students = Student::search($find, $user['id']);
    //     if (empty($students)) {
    //         $response->getBody()->write(json_encode(['message' => 'No students found matching the search criteria.']));
    //         return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    //     }
    //     $response->getBody()->write(json_encode($students));
    //     return $response->withHeader('Content-Type', 'application/json');
    // }

    // SEARCH
    public function search($request, $response, $args) {
        $params = $request->getQueryParams();

        // ตรวจสอบว่ามีพารามิเตอร์ 'find' หรือไม่
        if (!isset($params['find']) || empty(trim($params['find']))) {
            $response->getBody()->write(json_encode(['message' => 'Please provide a search term.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $find = trim($params['find']);
        $filter = trim($params['filter']);
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $perPage = 10; // กำหนดค่า perPage เป็น 10 ตายตัว


        // ดึง user_id จาก request
        $user = UserHelper::getUserFromRequest($request);

        // ค้นหานักเรียนพร้อมแบ่งหน้า
        $result = Student::searchWithPagination($filter, $find, $page, $perPage, $user['id']);

        // หากไม่มีผลลัพธ์
        if ($result['total_count'] === 0) {
            $response->getBody()->write(json_encode(['message' => 'No students found matching the search criteria.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        // คืนค่าผลลัพธ์
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    

}
