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

        $files = iterator_to_array($finder);
        usort($files, function ($a, $b) {
            // Stellen Sie sicher, dass breeder.sql immer zuerst geladen wird
            if ($a->getFilename() === 'breeder.sql') {
                return -1;
            }
            if ($b->getFilename() === 'breeder.sql') {
                return 1;
            }
            // Ansonsten sortieren Sie die Dateien alphabetisch
            return strcmp($a->getFilename(), $b->getFilename());
        });

        /** @var EntityManagerInterface $manager */
        $connection = $manager->getConnection();

        foreach ($files as $file) {
            $sql = $file->getContents();
            $connection->executeQuery($sql);

            $manager->flush();
        }
    }
}
