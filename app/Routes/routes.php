<?php

use Slim\App;
use App\Middleware\FirebaseAuthMiddleware;

return function (App $app) {
    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {
        //STUDENT
        $group->group('/students', function (\Slim\Routing\RouteCollectorProxy $students) {
            $students->get('/search', 'App\Controllers\StudentController:search');
            $students->get('', 'App\Controllers\StudentController:getAll'); 
            $students->get('/{id}', 'App\Controllers\StudentController:getById');
            $students->get('/status/{status}', 'App\Controllers\StudentController:getAllByStatus');
            $students->get('/page/{page}', 'App\Controllers\StudentController:getPaginatedStudents');
            $students->post('', 'App\Controllers\StudentController:create');
            $students->put('/{id}', 'App\Controllers\StudentController:updateFull'); 
            $students->put('/status/{id}', 'App\Controllers\StudentController:updateStatus');
            $students->delete('/{id}', 'App\Controllers\StudentController:delete'); 
        });

        //SCHOOL
        $group->group('/schools', function (\Slim\Routing\RouteCollectorProxy $schools) {
            $schools->get('', 'App\Controllers\SchoolController:getAll'); 
        });

        //TRIP
        $group->group('/trips', function (\Slim\Routing\RouteCollectorProxy $trips) {
            $trips->get('', 'App\Controllers\TripController:getAllByUserId'); 
        });

        //ROUTE
        $group->group('/routes', function (\Slim\Routing\RouteCollectorProxy $routes) {
            $routes->get('/{id}', 'App\Controllers\RouteController:getAllByTripId'); 
        });

        //PAX
        $group->group('/pax', function (\Slim\Routing\RouteCollectorProxy $pax) {
            $pax->get('/{id}', 'App\Controllers\PaxController:getAllByRouteId'); 
        });

        //DROPOFF
        $group->group('/drop-off', function (\Slim\Routing\RouteCollectorProxy $dropOff) {
            $dropOff->get('/{id}', 'App\Controllers\DropOffController:getAllByRouteId'); 
        });

    })->add(FirebaseAuthMiddleware::class); 
};

// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\App;
// use App\Middleware\FirebaseAuthMiddleware;

// return function (App $app) {

//     // STUDENT
//     $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {
//         // STUDENTS
//         $group->get('/students/search', 'App\Controllers\StudentController:search');
//         $group->get('/students', 'App\Controllers\StudentController:getAll'); // GET /api/students
//         $group->get('/students/{id}', 'App\Controllers\StudentController:getById'); // GET /api/students/{id}

//         $group->get('/students/status/{status}', 'App\Controllers\StudentController:getAllByStatus');
//         $group->get('/students/page/{page}', 'App\Controllers\StudentController:getPaginatedStudents');

//         $group->post('/students', 'App\Controllers\StudentController:create'); // POST /api/students

//         // $group->put('/students/{id}', 'App\Controllers\StudentController:update'); // PUT /api/students/{id}
//         $group->put('/students/{id}', 'App\Controllers\StudentController:updateFull');
//         $group->put('/students/status/{id}', 'App\Controllers\StudentController:updateStatus');
//         $group->delete('/students/{id}', 'App\Controllers\StudentController:delete'); // DELETE /api/students/{id}


//         // SCHOOLS
//         $group->get('/schools', 'App\Controllers\SchoolController:getAll');

//         // TRIPS
//         $group->get('/trips', 'App\Controllers\TripController:getAllByUserId');

//         // ROUTE
//         $group->get('/routes/{id}', 'App\Controllers\RouteController:getAllByTripId');

//         // PAX
//         $group->get('/pax/{id}', 'App\Controllers\PaxController:getAllByRouteId');

//         // Dropoff
//         $group->get('/drop-off/{id}', 'App\Controllers\DropOffController:getAllByRouteId');

//     })->add(FirebaseAuthMiddleware::class);
// };



