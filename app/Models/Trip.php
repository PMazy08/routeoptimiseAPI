<?php

namespace App\Models;

use App\Config\Database;

class Trip {
    // GET All BY USER ID
    public static function getAllByUserId($user_id) {
        $query = "SELECT trips.id, trips.dataTime, trips.types, schools.name as school
            FROM trips
            INNER JOIN schools ON trips.school_id = schools.id
            WHERE trips.user_id = ?
            ORDER BY trips.dataTime DESC";
                    
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }


    public static function create($data, $user_id)
    {
        $stmt = Database::connect()->prepare(
            "INSERT INTO trips (dataTime, school_id, user_id, types) 
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['dateTime'],   
            $data['school_id'],  
            $user_id,            
            $data['types']       
        ]);

        $tripId = Database::connect()->lastInsertId();
        
        // ดึง trip_id ของ trip ที่เพิ่มเข้ามา
        return $tripId;
    }
    
}