<?php

namespace App\Controller;

use App\Services\CannaConsultantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CannaConsultantController extends AbstractController
{
    public function __construct(
        private CannaConsultantService $cannaConsultantService,
    ){
    }

    #[Route('/canna-consultant', name: 'weedwizard_canna_consultant')]
    #[IsGranted('ROLE_USER', message: 'Du musst angemeldet sein um den Canna-Consultant zu nutzen.')]
    public function index(): Response
    {
        $messages = $this->cannaConsultantService->getRecentMessages();

        return $this->render('canna_consultant/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/canna-consultant/add-message', name: 'weedwizard_canna_consultant_add_message')]
    public function addMessage(Request $request): Response
    {
        $message = $request->get('message');

        $response = $this->cannaConsultantService->addMessageToThread($message);
        return new JsonResponse($response);
    }
}
