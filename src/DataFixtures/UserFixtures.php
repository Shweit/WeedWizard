<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
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

        $this->setReference(self::USER_REFERENCE, $user);
    }
}
