<?php

namespace App\Controller;

use App\Entity\CannabisVerein;
use App\Form\CannabisVereinType;
use App\Repository\CannabisVereinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannabisVereinController extends AbstractController
{

    private CannabisVereinRepository $cannabisVereinRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->cannabisVereinRepository = $this->entityManager->getRepository(CannabisVerein::class);
    }

    #[Route('/cannabis-verein', name: 'app_cannabis_verein')]
    public function index(): Response
    {
        $form = $this->createForm(CannabisVereinType::class);
        $cannabisVereine = $this->cannabisVereinRepository->findAll();

        return $this->render('cannabis_verein/index.html.twig', [
            'form' => $form->createView(),
            'cannabisVereine' => $cannabisVereine,
        ]);
    }
}
