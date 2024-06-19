<?php

namespace App\Controller;

use App\Entity\Breeder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class APIController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route('/api/getStrains/{breeder_id}', name: 'api_get_strains')]
    public function getStrainByBreeder(string $breeder_id): Response
    {
        $breeder = $this->entityManager->getRepository(Breeder::class)->findOneBy(['seedfinder_id' => $breeder_id]);

        $strains = $breeder->getStrains()->toArray();

        return new JsonResponse($this->serializer->normalize($strains, null, ['groups' => 'growMate']), Response::HTTP_OK); // @phpstan-ignore-line
    }
}
