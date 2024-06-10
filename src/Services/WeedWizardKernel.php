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
}
