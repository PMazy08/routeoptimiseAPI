<?php

namespace App\Controllers;

use App\Models\Route;

class RouteController{
    // GET ALL BY Trip Id
    public function getAllByTripId($request, $response,  $args){
        $trip_id = $args['id']; 
        $result = Route::getAllByTripId($trip_id);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
