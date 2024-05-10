<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const USER_REFERENCE_1 = 'USER_REFERENCE_1';
    const USER_REFERENCE_2 = 'USER_REFERENCE_2';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Create a first user
        $user = new User();

        $user->setEmail('dev@weedwizard.de');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'SicheresPasswort'
            )
        );

        $user->setBirthdate(new \DateTime('1990-01-01'));
        $user->setFirstname('Max');
        $user->setLastname('Mustermann');

        $manager->persist($user);
        $manager->flush();
        $this->setReference(self::USER_REFERENCE_1, $user);

        // Create a second user
        $user = new User();

        $user->setEmail('dev2@weedwizard.de');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'SichereresPasswort'
            )
        );

        $user->setBirthdate(new \DateTime('1990-01-01'));
        $user->setFirstname('Erika');
        $user->setLastname('Musterfrau');

        $manager->persist($user);
        $manager->flush();
        $this->setReference(self::USER_REFERENCE_2, $user);
    }
}
