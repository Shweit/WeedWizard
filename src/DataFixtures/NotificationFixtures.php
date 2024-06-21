<?php

namespace App\DataFixtures;

use App\Entity\Notification;
use App\Services\NotificationService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $notification = new Notification();
        $notification->setType(NotificationService::CANNA_CONSULTANT_TYPE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Canna Consultant');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setType(NotificationService::CANNADOSE_CALCULATOR_TYPE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Canna Dose Calculator');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setType(NotificationService::CANNASTRAIN_LIBRARY_TYPE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Cannastrain Library');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setType(NotificationService::BUD_BASH_LOCATOR_TYPE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Bud Bash Locator');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setType(NotificationService::CANNABIS_VEREINSSUCHE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Sozial Club');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setType(NotificationService::CANNABIS_CONSUMPTION_COMPLIANCE_MAP_TYPE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Cannabis Consumption Compliance Map');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setType(NotificationService::GROW_MATE_TYPE);
        $notification->setText('Dies ist eine Testbenachrichtigung vom Typ Grow Mate');
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUser($this->getReference(UserFixtures::USER_REFERENCE_1));
        $manager->persist($notification);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
