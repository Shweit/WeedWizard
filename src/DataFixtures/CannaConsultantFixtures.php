<?php

namespace App\DataFixtures;

use App\Entity\CannaConsultantThreads;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CannaConsultantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE_1);

        $thread = new CannaConsultantThreads();
        $thread->setUser($user);
        $thread->setThread([
            'id' => 'thread_ikFUSEnfJJr2NXwsYtHGu4cq',
            'object' => 'thread',
            'metadata' => [],
            'created_at' => 1717328430,
            'tool_resources' => [],
        ]);

        $manager->persist($thread);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
