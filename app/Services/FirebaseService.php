<?php

namespace App\Services;

use GuzzleHttp\Client;

class FirebaseService
{
    protected $client;
    protected $baseUrl;
    protected $firestoreUrl;
    protected $projectId;

    public function __construct()
    {
        $this->client       = new Client();
        $this->baseUrl      = env('FIREBASE_DATABASE_URL');
        $this->projectId    = env('FIREBASE_PROJECT_ID');
        $this->firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    // ===== REALTIME DATABASE (sensor) =====
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

    // ===== FIRESTORE (user) =====
    public function getUser($email)
    {
        $docId = strtolower(str_replace(['.', '@'], '_', $email));

        try {
            $response = $this->client->get("{$this->firestoreUrl}/allowed_users/{$docId}");
            $data     = json_decode($response->getBody(), true);

            if (!isset($data['fields'])) return null;

            // Parse Firestore format ke array biasa
            $fields = $data['fields'];
            return [
                'name'     => $fields['name']['stringValue']     ?? null,
                'email'    => $fields['email']['stringValue']    ?? null,
                'password' => $fields['password']['stringValue'] ?? null,
            ];
        } catch (\Exception $e) {
    \Log::error('FirebaseService getUser error: ' . $e->getMessage());
    return null;
}
    }

    public function updateLastLogin($email)
    {
        $docId = strtolower(str_replace(['.', '@'], '_', $email));

        $this->client->patch(
            "{$this->firestoreUrl}/allowed_users/{$docId}?updateMask.fieldPaths=last_login",
            [
                'json' => [
                    'fields' => [
                        'last_login' => ['stringValue' => now()->toDateTimeString()]
                    ]
                ]
            ]
        );
    }
}