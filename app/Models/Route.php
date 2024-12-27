<?php

namespace App\Models;

use App\Config\Database;

class Route {

    // GET All BY TRIP_ID
    public static function getAllByTripId($trip_id){
        $query = "SELECT * FROM routes WHERE trip_id = ? ";
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$trip_id]);
        return $stmt->fetchAll();
    }
}