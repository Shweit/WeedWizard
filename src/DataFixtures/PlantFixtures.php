<?php

namespace App\DataFixtures;

use App\Entity\Breeder;
use App\Entity\Plant;
use App\Entity\User;
use App\Services\CannaConsultantServiceV2;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly CannaConsultantServiceV2 $cannaConsultantService) {}

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
        $breeder3 = $manager->getRepository(Breeder::class)->find(6);

        $strain1 = $breeder1->getStrains()->get(array_rand($breeder1->getStrains()->toArray()));
        $strain2 = $breeder2->getStrains()->get(array_rand($breeder2->getStrains()->toArray()));
        $strain3 = $breeder3->getStrains()->get(array_rand($breeder3->getStrains()->toArray()));
        $strain4 = $breeder2->getStrains()->get(array_rand($breeder2->getStrains()->toArray()));

        // Create plants
        $plant1 = new Plant();
        $plant1->setName('Plant 1');
        $plant1->setDate(new \DateTime('now - 7 days'));
        $plant1->setState('Vegetativ');
        $plant1->setPlaceOfCultivation('Innen');
        $plant1->setLighting('LED');
        $plant1->setUser($user1);
        $plant1->setStrain($strain1);
        $plant1->setBreeder($breeder1);
        $plant1->setGrowth(1);

        // Get a random date between now and 7 days ago
        $watering = new \DateTime('now - ' . random_int(0, 7) . ' days');
        $wateringDateString = $watering->format('Y-m-d H:i:s');

        $fertilize = new \DateTime('now - ' . random_int(0, 3) . ' days');
        $fertilizeDateString = $fertilize->format('Y-m-d H:i:s');

        $temperature = new \DateTime('now - ' . random_int(0, 5) . ' days');
        $temperatureDateString = $temperature->format('Y-m-d H:i:s');

        $plant1->setWeeklyTasks([
            'water' => [
                [
                    'date' => $wateringDateString,
                    'task' => 'water',
                ],
            ],
            'fertilize' => [
                [
                    'date' => $fertilizeDateString,
                    'task' => 'fertilize',
                ],
            ],
            'temperature' => [
                [
                    'date' => $temperatureDateString,
                    'task' => 'temperature',
                ],
            ],
        ]);
        $plant1->setCurrentPrognosisValue(80);
        $plant1->setThread($this->cannaConsultantService->getThreadForPlant($plant1));

        $manager->persist($plant1);

        $plant2 = new Plant();
        $plant2->setName('BOB');
        $plant2->setDate(new \DateTime('now - 10 days'));
        $plant2->setState('Blüte');
        $plant2->setPlaceOfCultivation('Außen');
        $plant2->setLighting('Natural');
        $plant2->setUser($user1);
        $plant2->setStrain($strain2);
        $plant2->setBreeder($breeder2);
        $plant2->setGrowth(1);

        $watering = new \DateTime('now - ' . random_int(0, 7) . ' days');
        $wateringDateString = $watering->format('Y-m-d H:i:s');

        $fertilize = new \DateTime('now - ' . random_int(0, 3) . ' days');
        $fertilizeDateString = $fertilize->format('Y-m-d H:i:s');

        $temperature = new \DateTime('now - ' . random_int(0, 5) . ' days');
        $temperatureDateString = $temperature->format('Y-m-d H:i:s');

        $pesticide = new \DateTime('now - ' . random_int(0, 5) . ' days');
        $pesticideDateString = $pesticide->format('Y-m-d H:i:s');

        $plant2->setWeeklyTasks([
            'water' => [
                [
                    'date' => $wateringDateString,
                    'task' => 'water',
                ],
            ],
            'fertilize' => [
                [
                    'date' => $fertilizeDateString,
                    'task' => 'fertilize',
                ],
            ],
            'temperature' => [
                [
                    'date' => $temperatureDateString,
                    'task' => 'temperature',
                ],
            ],

            'pesticide' => [
                [
                    'date' => $pesticideDateString,
                    'task' => 'pesticide',
                ],
            ],
        ]);
        $plant2->setCurrentPrognosisValue(95);
        $plant2->setThread($this->cannaConsultantService->getThreadForPlant($plant2));

        $manager->persist($plant2);

        $plant3 = new Plant();
        $plant3->setName('Plant 1');
        $plant3->setDate(new \DateTime('2024-07-01'));
        $plant3->setState('Blüte');
        $plant3->setPlaceOfCultivation('Innen');
        $plant3->setLighting('LED');
        $plant3->setUser($user2);
        $plant3->setStrain($strain2);
        $plant3->setBreeder($breeder2);
        $plant3->setGrowth(1);
        $plant3->setWeeklyTasks([
            'water' => [
                [
                    'date' => '2024-07-17 08:30:00',
                    'task' => 'water',
                ],
            ],
            'fertilize' => [
                [
                    'date' => '2024-07-18 08:30:00',
                    'task' => 'fertilize',
                ],
            ],
            'temperature' => [
                [
                    'date' => '2024-07-19 08:30:00',
                    'task' => 'temperature',
                ],
            ],
        ]);
        $plant3->setCurrentPrognosisValue(-30);
        $plant3->setThread($this->cannaConsultantService->getThreadForPlant($plant3));

        $manager->persist($plant3);

        // Create plant4
        $plant4 = new Plant();
        $plant4->setName('Plant 2');
        $plant4->setDate(new \DateTime('2024-07-18'));
        $plant4->setState('Vegetativ');
        $plant4->setPlaceOfCultivation('Außen');
        $plant4->setLighting('Sonne');
        $plant4->setUser($user2);
        $plant4->setStrain($strain3);
        $plant4->setBreeder($breeder3);
        $plant4->setGrowth(1);
        $plant4->setWeeklyTasks([
            'water' => [
                [
                    'date' => '2024-07-18 09:00:00',
                    'task' => 'water',
                ],
            ],
            'fertilize' => [
                [
                    'date' => '2024-07-19 09:00:00',
                    'task' => 'fertilize',
                ],
            ],
            'temperature' => [
                [
                    'date' => '2024-07-20 09:00:00',
                    'task' => 'temperature',
                ],
            ],
            'pesticide' => [
                [
                    'date' => '2024-07-20 18:48:10',
                    'task' => 'pesticide',
                ],
            ],
        ]);
        $plant4->setCurrentPrognosisValue(85);
        $plant4->setThread($this->cannaConsultantService->getThreadForPlant($plant4));

        $manager->persist($plant4);

        // Create plant5
        $plant5 = new Plant();
        $plant5->setName('Plant 5');
        $plant5->setDate(new \DateTime('2024-07-01'));
        $plant5->setState('Vegetative');
        $plant5->setPlaceOfCultivation('Außen');
        $plant5->setLighting('Sonne');
        $plant5->setUser($user2);
        $plant5->setStrain($strain4);
        $plant5->setBreeder($breeder2);
        $plant5->setGrowth(1);
        $plant5->setWeeklyTasks([
            'water' => [
                [
                    'date' => '2024-07-05 10:00:00',
                    'task' => 'water',
                ],
                [
                    'date' => '2024-07-11 10:00:00',
                    'task' => 'water',
                ],
                [
                    'date' => '2024-07-17 10:00:00',
                    'task' => 'water',
                ],
            ],
            'fertilize' => [
                [
                    'date' => '2024-07-09 10:00:00',
                    'task' => 'fertilize',
                ],
                [
                    'date' => '2024-07-20 10:00:00',
                    'task' => 'fertilize',
                ],
            ],
            'temperature' => [
                [
                    'date' => '2024-07-03 10:00:00',
                    'task' => 'temperature',
                ],
                [
                    'date' => '2024-07-07 10:00:00',
                    'task' => 'temperature',
                ],
            ],
            'pesticide' => [
                [
                    'date' => '2024-07-14 10:00:00',
                    'task' => 'pesticide',
                ],
            ],
        ]);
        $plant5->setCurrentPrognosisValue(90);
        $plant5->setThread($this->cannaConsultantService->getThreadForPlant($plant5));

        $manager->persist($plant5);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            StrainWithBreederFixtures::class,
        ];
    }
}
