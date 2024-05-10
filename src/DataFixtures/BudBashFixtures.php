<?php

namespace App\DataFixtures;

use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BudBashFixtures extends Fixture implements DependentFixtureInterface
{
    private readonly ObjectManager $objectManager;

    public function load(ObjectManager $manager): void
    {
        $this->objectManager = $manager;
        $this->createPartyFromUser1();
        $this->createPartyFromUser2();
    }

    private function createPartyFromUser1(): void {
        $budBash = new BudBash();
        $budBash->setName('Party 1');
        $budBash->setStart(new \DateTime('tomorrow 8pm'));
        $budBash->setAddress('Partystraße 1, 12345 Partyhausen');
        $budBash->setEntranceFee(5.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->setCoordinates('52.520008,13.404954');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_1), true));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_2), true));
        $budBash->setExtraInfo("Das wird die beste Party des Jahres! Kommt alle vorbei!");

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();


        $budBash = new BudBash();
        $budBash->setName('Party 2');
        $budBash->setStart(new \DateTime('1 week 8pm'));
        $budBash->setAddress('Partystraße 1, 12345 Partyhausen');
        $budBash->setEntranceFee(5.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->setCoordinates('52.520008,13.404954');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();
    }

    private function createPartyFromUser2(): void {
        $budBash = new BudBash();
        $budBash->setName('Krasse Party und so');
        $budBash->setStart(new \DateTime('1 week 8pm'));
        $budBash->setAddress('Partystraße 1, 12345 Partyhausen');
        $budBash->setEntranceFee(5.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->setCoordinates('52.520008,13.404954');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_2), true));

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();


        $budBash = new BudBash();
        $budBash->setName('I <3 Nicolai');
        $budBash->setStart(new \DateTime('1 week 8pm'));
        $budBash->setAddress('Partystraße 1, 12345 Partyhausen');
        $budBash->setEntranceFee(5.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->setCoordinates('52.520008,13.404954');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->setExtraInfo("Nicolai ist der Beste! Deswegen witme ich diese Party ihm! <3");

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();
    }

    private function createBudBashCheckAttendance(BudBash $budBash, User $user, bool $checkedAttendance): BudBashCheckAttendance {
        $budBashCheckAttendance = new BudBashCheckAttendance();
        $budBashCheckAttendance->setBudBashParty($budBash);
        $budBashCheckAttendance->setParticipant($user);
        $budBashCheckAttendance->setCheckedAttendance($checkedAttendance);

        return $budBashCheckAttendance;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}