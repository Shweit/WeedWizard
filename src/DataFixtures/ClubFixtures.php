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
        $amount = 10;

        for ($i = 1; $i <= $amount; ++$i) {
            $user = new User();

            $user->setFirstname('User-' . $i);
            $user->setLastname('User-' . $i);
            $user->setBirthdate(new \DateTime('now - ' . rand(18, 50) . ' years'));
            $user->setEmail($this->generateRandomEmail());
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'SicheresPasswort'
                )
            );

            $address = explode(' $ ', $this->generateRandomAddres($i));

            $club = new CannabisVerein();
            $club->setName('Club-' . $i);
            $club->setAdresse($address[0]);
            $club->setCoordinaten($address[1]);
            $club->setWebsite('dummy-verein.de');
            $club->setMitgliedsbeitrag(strval(rand(100, 10000) / 100));
            $club->setBeschreibung($loremIpsum);
            $club->setSonstiges('Sonst gibts nichts.');
            $club->setCreatedBy($user);
            $club->addParticipant($user);

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

    private function generateRandomAddres(int $i): string
    {
        $addresses = [
            'Kurfürstendamm ' . $i . ', 10719 Berlin $ 52.5034,13.3323',
            'Hauptstrasse ' . $i . ', 01067 Dresden $ 51.0493,13.7384',
            'Königsallee ' . $i . ', 40212 Düsseldorf $ 51.2277,6.7735',
            'Kaiserstrasse ' . $i . ', 60311 Frankfurt am Main $ 50.1146,8.6797',
            'Luisenplatz ' . $i . ', 64283 Darmstadt $ 49.8728,8.6512',
            'Königstrasse ' . $i . ', 70173 Stuttgart $ 48.7784,9.1800',
            'Königsplatz ' . $i . ', 86150 Augsburg $ 48.3655,10.8944',
            'Königstrasse ' . $i . ', 90402 Nürnberg $ 49.4521,11.0768',
            'Königstrasse ' . $i . ', 01097 Dresde $ 51.0736,13.7407',
            'Im Schlank 41, 40472 Düsseldorf $ 51.2799677,6.79273',
        ];

        return $addresses[rand(0, count($addresses) - 1)];
    }
}
