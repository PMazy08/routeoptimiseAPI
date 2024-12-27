<?php

namespace App\Controllers;

use App\Models\DropOff;


class DropOffController{
    // GET ALL BY route_id
    public function getAllByRouteId($request, $response,  $args){
        $trip_id = $args['id']; 
        $result = DropOff::getAllByRouteId($trip_id);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}