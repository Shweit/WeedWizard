<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SeedFinderApiService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function getMostPopularStrains(int $limit = 6): array
    {
        try {
            $response = $this->client->request('GET', 'https://de.seedfinder.eu/api/json/', [
                'query' => [
                    'q' => 'getTopStrains',
                    'l' => $limit,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception('API request failed with status code ' . $response->getStatusCode());
            }

            return $response->toArray();

        } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new Exception('An error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
