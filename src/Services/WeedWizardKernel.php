<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class WeedWizardKernel
{
    private ?User $user;

    public function __construct(
        private readonly Security $security
    ) {
        /** @var User|null $user */
        $user = $this->security->getUser();
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isUserPremium(): bool
    {
        return $this->user && in_array('ROLE_PREMIUM', $this->user->getRoles());
    }
}
