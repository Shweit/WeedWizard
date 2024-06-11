<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SeedFinderApiService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, private readonly string $apikey)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    public function getBreederInfo(bool $withStrains, string $breederId = 'all', int $limit = 8)
    {
        $url = 'https://de.seedfinder.eu/api/json/ids.json?br=' .
            $breederId .
            ($withStrains ? '&strains=1&' : '&') . 'ac=' .
            $this->apikey;

        try {
            $response = $this->fetchApiDataViaCurl($url);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            throw new Exception('An error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return $this->decodeAndSliceJson($response, $limit);
    }

    /**
     * @throws Exception
     */
    public function getStrainsByBreeder(string $breederName, int $limit = 8)
    {
        $url = 'https://de.seedfinder.eu/api/json/ids.json?br=' .
            $breederName . 'ac=' .
            $this->apikey;

        try {
            $response = $this->fetchApiDataViaCurl($url);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            throw new Exception('An error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return $this->decodeAndSliceJson($response, $limit);
    }

    private function fetchApiDataViaCurl($url): bool|string
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $response = curl_exec($curlHandle);
        curl_close($curlHandle);

        return $response;
    }

    private function decodeAndSliceJson(bool|string $response, int $limit)
    {
        $json = json_decode($response, true);

        if (is_array($json)) {
            $json = array_slice($json, 0, $limit);
        } else {
            $json = ['error' => 'Failed to decode JSON'];
        }
        return $json;
    }
}
