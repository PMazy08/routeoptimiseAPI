<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Student;
use App\Models\User;

class StudentController
{
    // ฟังก์ชันช่วยเหลือในการดึงข้อมูลผู้ใช้
    private function getUserFromRequest(Request $request) {
        $uid = $request->getAttribute('uid');
        $user = User::getByUID($uid);
        if (!$user) {
            throw new \Exception('User not found');
        }
        return $user;
    }

    // GET ALL
    public function getAll(Request $request, Response $response, $args)
    {
        try {
            $user = $this->getUserFromRequest($request); 
            $result = Student::getAll($user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // GET BY ID
    public function getById(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];
            $user = $this->getUserFromRequest($request); 
            $result = Student::getById($id, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // GET All BY Status
    public function getAllByStatus(Request $request, Response $response, $args)
    {
        try {
            $status = $args['status'] === 'true' ? 1 : ($args['status'] === 'false' ? 0 : null);
            if (is_null($status)) {
                $response->getBody()->write(json_encode(['error' => 'Invalid status']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $user = $this->getUserFromRequest($request); 
            $result = Student::getAllByStatus($status, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // // GET 10 BY Page
    public function getPaginatedStudents(Request $request, Response $response, $args)
    {
        try {
            $page = isset($args['page']) ? (int)$args['page'] : 1;
            $perPage = 10; // จำนวนข้อมูลที่จะแสดงต่อหน้า
            $user = $this->getUserFromRequest($request); 
            $students = Student::getAllPaginated($page, $perPage, $user['id']);
            $response->getBody()->write(json_encode($students));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return $response->withStatus(404)->withJson(['error' => $e->getMessage()]);
        }
    }

    // CREATE
    public function create(Request $request, Response $response, array $args): Response
    {
        try {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            $user = $this->getUserFromRequest($request); 
            $result = Student::create($data, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // UPDATE ALL
    public function updateAll(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'];
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            $user = $this->getUserFromRequest($request); 
            $result = Student::updateAll($id, $data, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // UPDATE STATUS
    public function updateStatus(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $args['id'];
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            $user = $this->getUserFromRequest($request); 
            $result = Student::updateStatus($id, $data, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // DELETE
    public function delete(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'];
            $user = $this->getUserFromRequest($request); 
            $result = Student::delete($id, $user['id']);
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    // SEARCH
    public function search(Request $request, Response $response, array $args): Response
    {
        try {
            $params = $request->getQueryParams();

            if (!isset($params['find']) || empty($params['find'])) {
                $response->getBody()->write(json_encode(['message' => 'Please provide a name to search.']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $find = $params['find'];
            $user = $this->getUserFromRequest($request);
            $students = Student::search($find, $user['id']);

            if (empty($students)) {
                $response->getBody()->write(json_encode(['message' => 'No students found matching the search criteria.']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $response->getBody()->write(json_encode($students));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}
