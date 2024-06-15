<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserProfileController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
    ) {

    }

    #[Route('/profile/{username}', name: 'weedwizard_user_profile')]
    public function index(string $username): Response
    {
        $userProfile = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        return $this->render('user_profile/index.html.twig', [
            'userProfile' => $userProfile,
        ]);
    }
}
