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
    private ObjectManager $objectManager;

    public function load(ObjectManager $manager): void
    {
        $this->objectManager = $manager;
        $this->createPartyFromUser1();
        $this->createPartyFromUser2();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    private function createPartyFromUser1(): void
    {
        $budBash = new BudBash();
        $budBash->setName('Party 1');
        $budBash->setStart(new \DateTime('tomorrow 8pm'));
        $budBash->setAddress('Hildebrandtstraße 24c, 40215 Düsseldorf, Deutschland');
        $budBash->setEntranceFee(15.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->setCoordinates('51.209840,6.784880');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_1), true));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_2), false));
        $budBash->setExtraInfo('Das wird die beste Party des Jahres! Kommt alle vorbei!');

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();

        $budBash = new BudBash();
        $budBash->setName('Party 2');
        $budBash->setStart(new \DateTime('1 week 8pm'));
        $budBash->setAddress('Domkloster 4, 50667 Köln, Deutschland');
        $budBash->setEntranceFee(5.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->setCoordinates('50.941299,6.957190');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();

        $budBash = new BudBash();
        $budBash->setName('Party 3');
        $budBash->setStart(new \DateTime('now + 20min'));
        $budBash->setAddress('Lübecker Str. 6, 41540 Dormagen, Deutschland');
        $budBash->setEntranceFee(0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->setCoordinates('51.093550,6.813880');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_1));
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_1), true));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_2), false));

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();
    }

    private function createPartyFromUser2(): void
    {
        $budBash = new BudBash();
        $budBash->setName('Krasse Party und so');
        $budBash->setStart(new \DateTime('1 week 8pm'));
        $budBash->setAddress('Josef-Wolter-Weg 2, 41569 Rommerskirchen, Deutschland');
        $budBash->setEntranceFee(30.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->setCoordinates('51.039260,6.689630');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->addBudBashCheckAttendance($this->createBudBashCheckAttendance($budBash, $this->getReference(UserFixtures::USER_REFERENCE_2), true));

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();

        $budBash = new BudBash();
        $budBash->setName('I <3 Nicolai');
        $budBash->setStart(new \DateTime('1 week 8pm'));
        $budBash->setAddress('Friedrichstraße 133, 40217 Düsseldorf, Deutschland');
        $budBash->setEntranceFee(10.0);
        $budBash->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->setCoordinates('51.208450,6.775880');
        $budBash->addParticipant($this->getReference(UserFixtures::USER_REFERENCE_2));
        $budBash->setExtraInfo('Nicolai ist der Beste! Deswegen witme ich diese Party ihm! <3');

        $this->objectManager->persist($budBash);
        $this->objectManager->flush();
    }

    private function createBudBashCheckAttendance(BudBash $budBash, User $user, bool $checkedAttendance): BudBashCheckAttendance
    {
        $budBashCheckAttendance = new BudBashCheckAttendance();
        $budBashCheckAttendance->setBudBashParty($budBash);
        $budBashCheckAttendance->setParticipant($user);
        $budBashCheckAttendance->setCheckedAttendance($checkedAttendance);
        $budBashCheckAttendance->setSecretString($this->generateRandomString(20));

        return $budBashCheckAttendance;
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
