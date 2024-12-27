<?php

namespace App\Models;

use App\Config\Database;

class DropOff {
    // GET All BY USER ID
    public static function getAllByRouteId($route_id) {
        $query = "SELECT * FROM drop_off WHERE route_id = ?";
        $stmt = Database::connect()->prepare($query);
        $stmt->execute([$route_id]);
        return $stmt->fetchAll();
    }
}