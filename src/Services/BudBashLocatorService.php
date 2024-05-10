<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BudBashLocatorService
{

    public function __construct(
        private HttpClientInterface $httpClient,
    )
    {}

    public function getBudBashCoordinates(string $mapbox_id): array
    {
        $response = $this->httpClient->request(
            'GET',
            'https://api.mapbox.com/search/searchbox/v1/retrieve/' . $mapbox_id,
            [
                'query' => [
                    'access_token' => $_ENV['MAPBOX_ACCESS_TOEKN'],
                    'session_token' => $_ENV['MAPBOX_SESSION_TOKEN'],
                ],
            ]
        );

        $data = $response->toArray();

        return [
            'latitude' => $data['features'][0]['geometry']['coordinates'][1],
            'longitude' => $data['features'][0]['geometry']['coordinates'][0],
        ];
    }

}