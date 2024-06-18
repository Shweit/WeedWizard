<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class StrainWithBreederFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/StrainFixtures');
        $finder->name('*.sql');
        $finder->files();
        $finder->sortByName();

        /** @var EntityManagerInterface $manager */
        $connection = $manager->getConnection();

        foreach ($finder as $file) {
            $sql = $file->getContents();
            $connection->executeQuery($sql);

            $manager->flush();
        }
    }
}
