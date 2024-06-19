<?php

namespace App\Services;

use App\Entity\Breeder;
use App\Entity\Strain;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class WeedWizardKernel
{
    private ?User $user;

    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
    ) {
        /** @var User|null $user */
        $user = $this->security->getUser();
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function getBreederChoices(): array
    {
        $breeders = $this->entityManager->getRepository(Breeder::class)->findAll();
        $choices = [];
        foreach ($breeders as $breeder) {
            $choices[$breeder->getName()] = $breeder->getSeedfinderId();
        }

        return $choices;
    }

    public function getStrainChoices(): array
    {
        $strains = $this->entityManager->getRepository(Strain::class)->findAll();
        $choices = [];
        foreach ($strains as $strain) {
            $choices[$strain->getName()] = $strain->getSeedfinderId();
        }

        return $choices;
    }

    public function isUserPremium(): bool
    {
        return $this->user && in_array('ROLE_PREMIUM', $this->user->getRoles());
    }
}
