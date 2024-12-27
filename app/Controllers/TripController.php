<?php

namespace App\Controllers;

use App\Models\Trip;
use App\Helpers\UserHelper;

class TripController{
    // GET All BY USER_ID
    public function getAllByUserId($request, $response){
        $user = UserHelper::getUserFromRequest($request); 
        $result = Trip::getAllByUserId($user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

}