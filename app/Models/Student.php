<?php

namespace App\Models;

use App\Config\Database;

class Student {
    // ฟังก์ชันตรวจสอบว่า student มีอยู่ในฐานข้อมูลหรือไม่
    private static function checkStudentExists($id, $user_id) {
        $stmt = Database::connect()->prepare("SELECT COUNT(*) FROM students WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        return $stmt->fetchColumn() > 0;
    }

    // ฟังก์ชันดึงข้อมูล student
    private static function getStudentData($user_id, $extra_condition = '') {
        $query = "SELECT * FROM students WHERE user_id = ?";
        if ($extra_condition) {
            $query .= " AND $extra_condition";
        }
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // GET ALL
    public static function getAll($user_id) {
        return self::getStudentData($user_id);
    }

    // GET BY ID
    public static function getById($id, $user_id) {
        $students = self::getStudentData($user_id, "id = $id");
        return $students ? $students[0] : null;
    }

    // GET All BY Status
    public static function getAllByStatus($status, $user_id) {
        return self::getStudentData($user_id, "status = $status");
    }

    // GET 10 BY Page
    public static function getAllPaginated($page = 1, $perPage = 10, $user_id) {
        $offset = ($page - 1) * $perPage;
    
        // ดึงจำนวนแถวทั้งหมด
        $countStmt = Database::connect()->prepare(
            "SELECT COUNT(*) as total_count FROM students WHERE user_id = :user_id"
        );
        $countStmt->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $countStmt->execute();
        $totalCount = $countStmt->fetch(\PDO::FETCH_ASSOC)['total_count'];
    
        // ดึงข้อมูลแบบแบ่งหน้า
        $stmt = Database::connect()->prepare(
            "SELECT * FROM students WHERE user_id = :user_id LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        // คืนค่าผลลัพธ์พร้อม total_count
        return [
            'total_count' => $totalCount,
            'per_page' => $perPage,
            'current_page' => $page,
            'students' => $students,
        ];
    }
    

    // CREATE
    // public static function create($data, $user_id) {
    //     var_dump($data);
    //     // ตรวจสอบค่าที่ได้รับ
    //     if (!is_string($data['student_id']) || 
    //         !is_string($data['first_name']) || 
    //         !is_string($data['last_name']) || 
    //         !is_numeric($data['age']) || 
    //         !is_string($data['gender']) || 
    //         !is_string($data['address']) || 
    //         !is_float($data['latitude']) || 
    //         !is_float($data['longitude']) || 
    //         !is_bool($data['status'])) {
            
    //         return ['error' => 'Invalid data type detected'];
    //     }

    //     // หากข้อมูลถูกต้อง ทำการ INSERT
    //     $stmt = Database::connect()->prepare(
    //         "INSERT INTO students (student_id, first_name, last_name, age, gender, address, latitude, longitude, status, user_id) 
    //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    //     );
    //     $stmt->execute([
    //         $data['student_id'], 
    //         $data['first_name'], 
    //         $data['last_name'], 
    //         $data['age'],
    //         $data['gender'],
    //         $data['address'], 
    //         $data['latitude'], 
    //         $data['longitude'],
    //         $data['status'],
    //         $user_id
    //     ]);

    //     return ['message' => 'Student created successfully'];
    // }

    public static function create($data, $user_id) {
        try {
            // ตรวจสอบค่าที่ได้รับ
            if (!isset($data['student_id'], $data['first_name'], $data['last_name'], $data['age'], 
                      $data['gender'], $data['address'], $data['latitude'], $data['longitude'], $data['status'])) {
                return ['error' => 'Missing required fields'];
            }
    
            $data['age'] = is_numeric($data['age']) ? (int)$data['age'] : null;
            $data['latitude'] = is_numeric($data['latitude']) ? (float)$data['latitude'] : null;
            $data['longitude'] = is_numeric($data['longitude']) ? (float)$data['longitude'] : null;
            $data['status'] = filter_var($data['status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    
            if (!$data['age'] || !$data['latitude'] || !$data['longitude'] || $data['status'] === null) {
                return ['error' => 'Invalid data type detected'];
            }
    
            // หากข้อมูลถูกต้อง ทำการ INSERT
            $stmt = Database::connect()->prepare(
                "INSERT INTO students (student_id, first_name, last_name, age, gender, address, latitude, longitude, status, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $data['student_id'], 
                $data['first_name'], 
                $data['last_name'], 
                $data['age'], 
                $data['gender'], 
                $data['address'], 
                $data['latitude'], 
                $data['longitude'], 
                $data['status'], 
                $user_id
            ]);
    
            return ['message' => 'Student created successfully'];
        } catch (\PDOException $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['error' => 'Unexpected error: ' . $e->getMessage()];
        }
    }
    


    // UPDATE ALL JSON
    public static function updateAll($id, $data, $user_id) {
        if (!self::checkStudentExists($id, $user_id)) {
            return ['error' => 'Unauthorized or student not found'];
        }

        $stmt = Database::connect()->prepare(
            "UPDATE students 
             SET first_name = ?, last_name = ?, age = ?, gender = ?, address = ?, latitude = ?, longitude = ?, status = ? 
             WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([
            $data['first_name'], 
            $data['last_name'], 
            $data['age'], 
            $data['gender'], 
            $data['address'], 
            $data['latitude'], 
            $data['longitude'], 
            $data['status'], 
            $id, 
            $user_id
        ]);
        return ['message' => 'Student updated successfully'];
    }

    // UPDATE Status
    public static function updateStatus($id, $data, $user_id) {
        if (!self::checkStudentExists($id, $user_id)) {
            return ['error' => 'Unauthorized or student not found'];
        }

        $stmt = Database::connect()->prepare("UPDATE students SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$data['status'], $id, $user_id]);
        return ['message' => 'Student updated successfully'];
    }

    // DELETE
    public static function delete($id, $user_id) {
        if (!self::checkStudentExists($id, $user_id)) {
            return ['error' => 'Unauthorized or student not found'];
        }

        $stmt = Database::connect()->prepare("DELETE FROM students WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);

        if ($stmt->rowCount() > 0) {
            return ['message' => 'Student deleted'];
        } else {
            return ['message' => 'No matching student found to delete'];
        }
    }

    // SEARCH
    // public static function search($find, $user_id) {
    //     $stmt = Database::connect()->prepare(
    //         "SELECT * FROM students 
    //          WHERE (first_name LIKE :find OR last_name LIKE :find OR address LIKE :find) 
    //          AND user_id = :user_id"
    //     );
    //     $stmt->execute([
    //         ':find' => '%' . $find . '%',
    //         ':user_id' => $user_id
    //     ]);
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    // }

    //searc2
    public static function searchWithPagination($find, $page = 1, $perPage = 10, $user_id) {
        $offset = ($page - 1) * $perPage;
    
        // Query ดึงจำนวนแถวทั้งหมดที่ตรงกับคำค้นหา
        $countStmt = Database::connect()->prepare(
            "SELECT COUNT(*) as total_count 
             FROM students 
             WHERE (first_name LIKE :find OR last_name LIKE :find OR address LIKE :find) 
             AND user_id = :user_id"
        );
        $countStmt->execute([
            ':find' => '%' . $find . '%',
            ':user_id' => $user_id
        ]);
        $totalCount = $countStmt->fetch(\PDO::FETCH_ASSOC)['total_count'];
    
        // Query ดึงข้อมูลแบบแบ่งหน้า
        $stmt = Database::connect()->prepare(
            "SELECT * 
             FROM students 
             WHERE (first_name LIKE :find OR last_name LIKE :find OR address LIKE :find) 
             AND user_id = :user_id 
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':find', '%' . $find . '%', \PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        // คืนค่าผลลัพธ์พร้อม total_count
        return [
            'total_count' => $totalCount,
            'per_page' => $perPage,
            'current_page' => $page,
            'students' => $students,
        ];
    }
    

}
