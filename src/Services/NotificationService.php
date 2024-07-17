<?php

namespace App\Services;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    // Hier kann mam typisierte Konstanten definieren, welche für die Notification
    // verwendet werden können.

    public const GENERAL_TYPE = 'general';
    public const CANNABIS_CONSUMPTION_COMPLIANCE_MAP_TYPE = 'cannabis_consumption_compliance_map';
    public const CANNABIS_VEREINSSUCHE = 'cannabis_vereinssuche';
    public const BUD_BASH_LOCATOR_TYPE = 'bud_bash_locator';
    public const CANNASTRAIN_LIBRARY_TYPE = 'cannastrain_library';
    public const CANNADOSE_CALCULATOR_TYPE = 'cannadose_calculator';
    public const GROW_MATE_TYPE = 'grow_mate';
    public const CANNA_CONSULTANT_TYPE = 'canna_consultant';
    public const BLOG_TYPE = 'blog';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
    ) {}

    public function createNotification(string $type, string $text, ?User $user = null): void
    {
        $message = new Notification();
        $message->setUser($user ?? $this->weedWizardKernel->getUser());
        $message->setType($type);
        $message->setText($text);
        $message->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}
