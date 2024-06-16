<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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

    public function redirectToPreviousPage(Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer') ?? '/';
        return new RedirectResponse($referer);
    }
}
