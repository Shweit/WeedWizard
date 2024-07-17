<?php

namespace App\DataFixtures;

use App\Entity\Plant;
use App\Entity\User;
use App\Entity\Strain;
use App\Entity\Breeder;
use App\Services\CannaConsultantService;
use App\Services\CannaConsultantServiceV2;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PlantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly CannaConsultantServiceV2 $cannaConsultantService)
    {
    }

    public function load(ObjectManager $manager)
    {
        // Load users from references created in UserFixtures
        /** @var User $user1 */
        $user1 = $this->getReference(UserFixtures::USER_REFERENCE_1);
        /** @var User $user2 */
        $user2 = $this->getReference(UserFixtures::USER_REFERENCE_2);

        // Load strains and breeders (assuming these fixtures exist)
        $breeder1 = $manager->getRepository(Breeder::class)->find(1);
        $breeder2 = $manager->getRepository(Breeder::class)->find(4);

        $strain1 = $breeder1->getStrains()->get(array_rand($breeder1->getStrains()->toArray()));
        $strain2 = $breeder2->getStrains()->get(array_rand($breeder2->getStrains()->toArray()));



        // Create plants
        $plant1 = new Plant();
        $plant1->setName('Plant 1');
        $plant1->setDate(new \DateTime('2023-01-01'));
        $plant1->setState('Growing');
        $plant1->setPlaceOfCultivation('Indoor');
        $plant1->setLighting('LED');
        $plant1->setUser($user1);
        $plant1->setStrain($strain1);
        $plant1->setBreeder($breeder1);
        $plant1->setGrowth(3);
        $plant1->setWeeklyTasks([
            "Watering",
            "Pruning",
            "water" => [
                [
                    "date" => "2024-07-17 18:48:09",
                    "task" => "water"
                ]
            ],
            "fertilize" => [
                [
                    "date" => "2024-07-17 18:48:10",
                    "task" => "fertilize"
                ]
            ],
            "temperature" => [
                [
                    "date" => "2024-07-17 18:48:10",
                    "task" => "temperature"
                ]
            ]
        ]);
        $plant1->setCurrentPrognosisValue(80);
        $plant1->setThread($this->cannaConsultantService->getThreadForPlant($plant1));


        $manager->persist($plant1);

        $plant2 = new Plant();
        $plant2->setName('Plant 2');
        $plant2->setDate(new \DateTime('2023-02-15'));
        $plant2->setState('Harvested');
        $plant2->setPlaceOfCultivation('Outdoor');
        $plant2->setLighting('Natural');
        $plant2->setUser($user2);
        $plant2->setStrain($strain2);
        $plant2->setBreeder($breeder2);
        $plant2->setGrowth(5);
        $plant2->setWeeklyTasks([
            "Watering",
            "Pruning",
            "water" => [
                [
                    "date" => "2024-07-17 18:48:09",
                    "task" => "water"
                ]
            ],
            "fertilize" => [
                [
                    "date" => "2024-07-17 18:48:10",
                    "task" => "fertilize"
                ]
            ],
            "temperature" => [
                [
                    "date" => "2024-07-17 18:48:10",
                    "task" => "temperature"
                ]
            ]
        ]);        $plant2->setCurrentPrognosisValue(95);
        $plant2->setThread($this->cannaConsultantService->getThreadForPlant($plant2));


        $manager->persist($plant2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            StrainWithBreederFixtures::class
        ];
    }
}
