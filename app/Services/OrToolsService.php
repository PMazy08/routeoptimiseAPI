<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class OrToolsService {
    private $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://127.0.0.1:5000',
            // 'timeout' => 20,  
        ]);
    }
    public function calculateRoutes($data) {
        try {
            // ส่งคำขอแบบ asynchronous
            $promise = $this->client->postAsync('/vrp/solve_vrp', [
                'headers' => [
                    'Content-Type' => 'application/json', // ระบุ Content-Type เป็น JSON
                ],
                'json' => $data, // ส่งข้อมูลในรูปแบบ JSON
            ]);

            // รอให้คำขอเสร็จสิ้น
            $response = $promise->wait();

            $body = $response->getBody();

            return json_decode($body, true); // แปลง JSON response เป็น Array
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
