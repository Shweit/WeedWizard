<?php

namespace App\Controller;

use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
    ) {}

    #[Route('/premium', name: 'weedwizard_premium')]
    public function upgradePremium(): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', 'Du musst eingeloggt sein um dein Abo zu ändern');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('index/premium.html.twig');
    }

    #[Route('/premium/{role}', name: 'weedwizard_premium_upgrade')]
    public function upgradePremiumRole($role): Response
    {
        // Check if user is logged in, if not redirect to login page
        // and show error flash message
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', 'Du musst eingeloggt sein um dein Abo zu ändern');

            return $this->redirectToRoute('app_login');
        }

        $role = match ($role) {
            'freemium' => [],
            'premium' => ['ROLE_PREMIUM'],
            default => [],
        };

        $user = $this->weedWizardKernel->getUser();
        $user->setRoles($role);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Aktualisiere den Token in der Sitzung
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);

        // Refresh the session to reflect new roles
        $session = $this->requestStack->getSession();
        $session->set('_security_main', serialize($token));

        return $this->redirectToRoute('weedwizard_premium');
    }
}
