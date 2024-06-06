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

    public function __construct(HttpClientInterface $client, private readonly string $apikey)
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

    public function getJsonTestData()
    {
        $url = "https://de.seedfinder.eu/api/json/ids.json?br=Serious_Seeds|Cannabiogen&strains=1&ac=".$this->apikey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response);

        dd($json);
    }
}
