<?php

namespace App\Controller;

use App\Services\CannaConsultantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannaConsultantController extends AbstractController
{
    public function __construct(
        private CannaConsultantService $cannaConsultantService,
    ) {}

    #[Route('/canna-consultant', name: 'weedwizard_canna_consultant')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_PREMIUM')) {
            $this->addFlash('error', 'Du brauchst ein Premium-Abo um den Canna-Consultant zu nutzen');

            return $this->redirectToRoute('weedwizard_premium');
        }

        $messages = $this->cannaConsultantService->getRecentMessages();

        return $this->render('canna_consultant/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/canna-consultant/add-message', name: 'weedwizard_canna_consultant_add_message')]
    public function addMessage(Request $request): Response
    {
        if (!$this->isGranted('ROLE_PREMIUM')) {
            $this->addFlash('error', 'Du brauchst ein Premium-Abo um den Canna-Consultant zu nutzen');

            return $this->redirectToRoute('weedwizard_premium');
        }

        $message = $request->get('message');

        $response = $this->cannaConsultantService->addMessageToThread($message);

        return new JsonResponse($response);
    }
}
