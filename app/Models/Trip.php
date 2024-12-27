<?php

namespace App\Models;

use App\Config\Database;

class Trip {
    // GET All BY USER ID
    public static function getAllByUserId($user_id) {
        $query = "SELECT trips.id, trips.dataTime, trips.types, schools.name as school
                    FROM trips
                    INNER JOIN schools ON trips.school_id = schools.id
                    WHERE trips.user_id = ?";
                    
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}