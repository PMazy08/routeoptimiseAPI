<?php

namespace App\Models;

use App\Config\Database;

class School {
    // ฟังก์ชันตรวจสอบว่า student มีอยู่ในฐานข้อมูลหรือไม่
    private static function checkStudentExists($id, $user_id) {
        $stmt = Database::connect()->prepare("SELECT COUNT(*) FROM students WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        return $stmt->fetchColumn() > 0;
    }

    // GET ALL
    public static function getAll($user_id) {
        $query = "SELECT * FROM schools WHERE user_id = ?";
        if ($extra_condition) {
            $query .= " AND $extra_condition";
        }
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

}