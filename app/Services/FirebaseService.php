<?php

namespace App\Services;

use GuzzleHttp\Client;

class FirebaseService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = env('FIREBASE_DATABASE_URL');
    }

    public function getData($path)
    {
        $response = $this->client->get("{$this->baseUrl}/{$path}.json");
        return json_decode($response->getBody(), true);
    }

    public function setData($path, $data)
    {
        $response = $this->client->put("{$this->baseUrl}/{$path}.json", [
            'json' => $data
        ]);
        return json_decode($response->getBody(), true);
    }
}