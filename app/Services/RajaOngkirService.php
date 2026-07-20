<?php

namespace App\Services;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = rtrim(env('RAJAONGKIR_BASE_URL'), '/');
    }

    public function getDestination($search, $limit = 50)
    {
        $client = service('curlrequest');
        $response = $client->get($this->baseUrl . '/destination/domestic-destination', [
            'headers' => [
                'key' => $this->apiKey,
            ],
            'query' => [
                'search' => $search,
                'limit' => $limit,
            ],
            'http_errors' => false,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getCost($origin, $destination, $weight, $courier)
    {
        $client = service('curlrequest');
        $response = $client->post($this->baseUrl . '/calculate/domestic-cost', [
            'headers' => [
                'key' => $this->apiKey,
            ],
            'form_params' => [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ],
            'http_errors' => false,
        ]);

        return json_decode($response->getBody(), true);
    }
}
