<?php

namespace App\Twig\Runtime;

use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class WeedWizardBudBashLocatorExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function iconForAttendance(BudBash $budBash, User $user): string
    {
        $budBashCheckAttendance = $this->entityManager->getRepository(BudBashCheckAttendance::class)->findOneBy([
            'BudBashParty' => $budBash,
            'participant' => $user,
        ]);

        if (!$budBashCheckAttendance) {
            return '';
        }

        if ($budBashCheckAttendance->isCheckedAttendance()) {
            return '<i class="fa-solid fa-check"></i>';
        }

        return '<i class="fa-solid fa-x"></i>';
    }

    public function secretStringForAttendance(BudBash $budBash, User $user): string
    {
        $budBashCheckAttendance = $this->entityManager->getRepository(BudBashCheckAttendance::class)->findOneBy([
            'BudBashParty' => $budBash,
            'participant' => $user,
        ]);

        return $budBashCheckAttendance->getSecretString();
    }
}
