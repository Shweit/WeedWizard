<?php

namespace App\Service;

use App\Entity\Breeder;
use App\Entity\Strain;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class SeedFinderApiService
{
    public function __construct(
        private readonly string $apikey,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {}

    public function getBreederInfo(string $breederId = 'all')
    {
        $breeders = match ($breederId) {
            'all' => $this->entityManager->getRepository(Breeder::class)->findAll(),
            default => $this->entityManager->getRepository(Breeder::class)->findOneBy(['seedfinder_id' => $breederId]),
        };

        return $this->serializer->normalize($breeders, null, ['groups' => 'cannastrainLibrary']);
    }

    public function getStrainInfo(string $breeder_id, string $strain_id)
    {
        $strain = $this->entityManager->getRepository(Strain::class)->findOneBy([
            'breeder' => $this->entityManager->getRepository(Breeder::class)->findOneBy(['seedfinder_id' => $breeder_id]),
            'seedfinder_id' => $strain_id,
        ]);

        return $this->serializer->normalize($strain, null, ['groups' => 'cannastrainLibrary']);
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

    private function decodeAndSliceJson(bool|string $response, int $limit = 100) // TODO: Set good limit
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
