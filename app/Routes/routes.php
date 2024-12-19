<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Middleware\FirebaseAuthMiddleware;

return function (App $app) {

    // STUDENT
    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {

        // STUDENTS
        $group->get('/students/search', 'App\Controllers\StudentController:search');
        $group->get('/students', 'App\Controllers\StudentController:getAll'); // GET /api/students
        $group->get('/students/{id}', 'App\Controllers\StudentController:getById'); // GET /api/students/{id}

        $group->get('/students/status/{status}', 'App\Controllers\StudentController:getAllByStatus');
        // $group->get('/students/page/{page}', 'App\Controllers\StudentController:getPaginatedStudents');

        $group->post('/students', 'App\Controllers\StudentController:create'); // POST /api/students

        $group->put('/students/{id}', 'App\Controllers\StudentController:update'); // PUT /api/students/{id}
        $group->put('/students/all/{id}', 'App\Controllers\StudentController:updateAll');
        $group->put('/students/status/{id}', 'App\Controllers\StudentController:updateStatus');
        $group->delete('/students/{id}', 'App\Controllers\StudentController:delete'); // DELETE /api/students/{id}
    })->add(FirebaseAuthMiddleware::class);



    
    // TEST
    $app->group('/test2', function (\Slim\Routing\RouteCollectorProxy $group) {

        $group->get('/students', 'App\Controllers\StudentController:getAll'); // GET /api/students

    })->add(FirebaseAuthMiddleware::class);
};



