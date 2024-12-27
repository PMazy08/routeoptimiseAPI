<?php

namespace App\Models;

use App\Config\Database;

class Pax {
    // GET All BY USER ID
    public static function getAllByRouteId($route_id) {
        $query = "SELECT pax.id as pax_id, students.student_id, students.first_name, students.last_name, students.age, students.gender, students.latitude, students.longitude
                    FROM pax
                    INNER JOIN students ON pax.student_id = students.student_id
                    WHERE pax.route_id = ?";
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$route_id]);
        return $stmt->fetchAll();
    }
}