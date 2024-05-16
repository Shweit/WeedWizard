<?php

namespace App\DataFixtures;

use App\Entity\CannabisVerein;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClubFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $loremIpsum = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
        $hausnummer = 10;

        for ($i = 1; $i <= $hausnummer; ++$i) {
            $user = new User();

            $user->setEmail($this->generateRandomEmail());
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'SicheresPasswort'
                )
            );

            $club = new CannabisVerein();
            $club->setName('Club-' . $i);
            $club->setAdresse('Hauptstraße ' . $i . ', 12345 Berlin');
            $club->setCoordinaten('52.520008, 13.404954');
            $club->setWebsite('dummy-verein.de');
            $club->setMitgliedsbeitrag(strval(rand(100, 10000) / 100));
            $club->setBeschreibung($loremIpsum);
            $club->setSonstiges('Sonst gibts nichts.');
            $club->setErstelltVon($user);
            $club->addMitglieder($user);

            $manager->persist($user);
            $manager->persist($club);
        }

        $manager->flush();
    }

    private function generateRandomEmail(): string
    {
        $email = rand(100, 100000);

        return $email . '@weedwizard.de';
    }
}
