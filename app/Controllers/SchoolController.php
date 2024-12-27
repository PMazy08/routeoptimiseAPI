<?php

namespace App\Controllers;

use App\Models\School;
use App\Helpers\UserHelper;

class SchoolController{
    // GET ALL
    public function getAll($request, $response){
        $user = UserHelper::getUserFromRequest($request); 
        $result = School::getAll($user['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }


}
