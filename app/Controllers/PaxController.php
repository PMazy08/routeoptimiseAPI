<?php

namespace App\Controllers;

use App\Models\Pax;


class PaxController{
    // GET ALL BY route_id
    public function getAllByRouteId($request, $response,  $args){
        $trip_id = $args['id']; 
        $result = Pax::getAllByRouteId($trip_id);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}