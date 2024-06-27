<?php

namespace App\Services;

use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Entity\Plant;
use App\Entity\Strain;
use App\Service\SeedFinderApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CannaConsultantFunctions
{
    protected function __construct(
        private EntityManagerInterface $entityManager,
        private WeedWizardKernel $weedWizardKernel,
        private SeedFinderApiService $seedFinderApiService,
        private SerializerInterface $serializer,
    ) {}

    protected function get_bud_bash_partys(): array
    {
        try {
            $budBashes = $this->entityManager->getRepository(BudBash::class)->findAll();

            $budBashes = array_filter($budBashes, function ($budBash) {
                return $budBash->getCreatedBy() !== $this->weedWizardKernel->getUser() && $budBash->getStart() > new \DateTime() && !$budBash->getParticipants()->contains($this->weedWizardKernel->getUser());
            });

            return array_map(function (BudBash $budBash) {
                return [
                    'id' => $budBash->getId(),
                    'name' => $budBash->getName(),
                    'description' => $budBash->getExtraInfo(),
                    'start' => $budBash->getStart()->format('d.m.Y H:i:s'),
                    'location' => $budBash->getAddress(),
                    'participants' => $budBash->getParticipants()->count(),
                ];
            }, $budBashes);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function add_user_to_party(int $budBash_id): string
    {
        try {
            $budBash = $this->entityManager->getRepository(BudBash::class)->find($budBash_id);

            if (!$budBash) {
                return 'Party not found';
            }

            if ($budBash->getCreatedBy() === $this->weedWizardKernel->getUser()) {
                return 'You can\'t join your own party';
            }

            if ($budBash->getStart() < new \DateTime()) {
                return 'Party already started';
            }

            if ($budBash->getParticipants()->contains($this->weedWizardKernel->getUser())) {
                return 'You are already participating in this party';
            }

            $budBash->addParticipant($this->weedWizardKernel->getUser());

            if ($budBash->getBudBashCheckAttendances()[0]) {
                $budBashCheckAttendance = new BudBashCheckAttendance();
                $budBashCheckAttendance->setParticipant($this->weedWizardKernel->getUser());
                $budBashCheckAttendance->setBudBashParty($budBash);
                $budBashCheckAttendance->setCheckedAttendance(false);
                $budBashCheckAttendance->setSecretString($this->weedWizardKernel->generateRandomString(20));

                $budBash->addBudBashCheckAttendance($budBashCheckAttendance);
            }

            $this->entityManager->flush();

            return 'You have successfully joined the party';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function get_attended_party(): array
    {
        try {
            $budbashes = $this->entityManager->getRepository(BudBash::class)->findAll();

            // find every party, where the user is a participant and which is NOT in the past
            // and where the user is NOT the creator of the party
            $budbashes = array_filter($budbashes, function ($budBash) {
                return $budBash->getParticipants()->contains($this->weedWizardKernel->getUser()) && $budBash->getStart() > new \DateTime('yesterday') && $budBash->getCreatedBy() !== $this->weedWizardKernel->getUser();
            });

            return array_map(function (BudBash $attendedParty) {
                return [
                    'id' => $attendedParty->getId(),
                    'name' => $attendedParty->getName(),
                    'description' => $attendedParty->getExtraInfo(),
                    'start' => $attendedParty->getStart()->format('d.m.Y H:i:s'),
                    'location' => $attendedParty->getAddress(),
                    'participants' => $attendedParty->getParticipants()->count(),
                ];
            }, $budbashes);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function get_all_breeders_and_strains(): array
    {
        try {
            return $this->seedFinderApiService->getBreederInfo();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function get_cannabis_breeder_info(string $breeder_id): array
    {
        try {
            return $this->seedFinderApiService->getBreederInfo($breeder_id);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function get_cannabis_strain_info(string $breeder_id, string $strain_id): array
    {
        try {
            return $this->seedFinderApiService->getStrainInfo($breeder_id, $strain_id);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function search_cannabis_strain(string $search): array
    {
        try {
            $repository = $this->entityManager->getRepository(Strain::class);

            $query = $repository->createQueryBuilder('s')
                ->where('s.name LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->getQuery();

            $results = $query->getResult();

            return $this->serializer->normalize($results, null, ['groups' => 'cannastrainLibrary']); // @phpstan-ignore-line
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function get_plant_info(int $plant_id): array
    {
        try {
            $plant = $this->entityManager->getRepository(Plant::class)->find($plant_id);

            if (!$plant) {
                return ['error' => 'Plant not found'];
            }

            if ($plant->getUser() !== $this->weedWizardKernel->getUser()) {
                return ['error' => 'You are not allowed to view this plant'];
            }

            return [
                'id' => $plant->getId(),
                'name' => $plant->getName(),
                'date' => $plant->getDate(),
                'state' => $plant->getState(),
                'placeOfCultivation' => $plant->getPlaceOfCultivation(),
                'lighting' => $plant->getLighting(),
                'breeder' => $plant->getBreeder(),
                'strain' => $plant->getStrain(),
                'growth' => $plant->getGrowth(),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
