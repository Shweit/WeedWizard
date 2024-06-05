<?php

namespace App\DataFixtures;

use App\Entity\MapMarkers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MapMarkersFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('USER_REFERENCE_1');

        $marker = new MapMarkers();
        $marker->setTitle('Test Marker');
        $marker->setDescription('Test Description');
        $marker->setCoordinates('51.03491742972195,6.711852550506593');
        $marker->setUser($user);
        $manager->persist($marker);
        $manager->flush();

        // Hier können weitere Marker hinzugefügt werden. Am besten Marker bei euch in der Nähe hinzufügen, damit ihr sie auch auf der Karte sehen könnt.
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}