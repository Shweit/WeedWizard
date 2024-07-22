<?php

namespace App\Service;

use App\Entity\Breeder;
use App\Entity\Strain;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Serializer\SerializerInterface;

class SeedFinderApiService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {}

    public function getBreederInfo(string $breederId = 'all')
    {
        $breeders = match ($breederId) {
            'all' => $this->entityManager->getRepository(Breeder::class)->findAll(),
            default => $this->entityManager->getRepository(Breeder::class)->findOneBy(['seedfinder_id' => $breederId]),
        };

        return $this->serializer->normalize($breeders, null, ['groups' => 'cannastrainLibrary']); // @phpstan-ignore-line
    }

    public function getBreederInfoPaginated(string $breederId = 'all', int $page = 1, int $paginationLimit = 8)
    {
        if ($breederId === 'all') {
            $query = $this->entityManager->getRepository(Breeder::class)
                ->createQueryBuilder('b')
                ->getQuery()
                ->setFirstResult(($page - 1) * $paginationLimit)
                ->setMaxResults($paginationLimit);

            $paginator = new Paginator($query);

            return $this->serializer->normalize($paginator, null, ['groups' => 'cannastrainLibrary']); // @phpstan-ignore-line
        }
        $breeder = $this->entityManager->getRepository(Breeder::class)
            ->findOneBy(['seedfinder_id' => $breederId]);

        return $this->serializer->normalize($breeder, null, ['groups' => 'cannastrainLibrary']); // @phpstan-ignore-line
    }

    public function getStrainInfo(string $breeder_id, string $strain_id)
    {
        $strain = $this->entityManager->getRepository(Strain::class)->findOneBy([
            'breeder' => $this->entityManager->getRepository(Breeder::class)->findOneBy(['seedfinder_id' => $breeder_id]),
            'seedfinder_id' => $strain_id,
        ]);

        return $this->serializer->normalize($strain, null, ['groups' => 'cannastrainLibrary']); // @phpstan-ignore-line
    }

    //    private function fetchApiDataViaCurl($url): bool|string
    //    {
    //        $curlHandle = curl_init();
    //        curl_setopt($curlHandle, CURLOPT_URL, $url);
    //        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    //        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
    //            'Content-Type: application/json',
    //        ]);
    //        $response = curl_exec($curlHandle);
    //        curl_close($curlHandle);
    //
    //        return $response;
    //    }
    //
    //    private function decodeAndSliceJson(bool|string $response, int $limit = 100)
    //    {
    //        $json = json_decode($response, true);
    //
    //        if (is_array($json)) {
    //            $json = array_slice($json, 0, $limit);
    //        } else {
    //            $json = ['error' => 'Failed to decode JSON'];
    //        }
    //
    //        return $json;
    //    }
}
