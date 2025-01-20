<?php

namespace App\Controllers;

use App\Services\OrToolsService;

class CalculateRouteController {
    private $orToolsService;

    public function __construct() {
        $this->orToolsService = new OrToolsService();
    }

    public function callOrTools($request, $response, $args) {
        $data = json_decode($request->getBody()->getContents(), true);
        $result = $this->orToolsService->calculateRoutes($data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}