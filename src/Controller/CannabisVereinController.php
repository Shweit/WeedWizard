<?php

namespace App\Controller;

use App\Entity\CannabisVerein;
use App\Form\CannabisVereinType;
use App\Services\CannabisVereinService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannabisVereinController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CannabisVereinService $cannabisVereinService
    ) {}

    #[Route('/cannabis-verein', name: 'cannabis_verein')]
    public function index(Request $request): Response
    {

        $newVerein = new CannabisVerein();
        $form = $this->createForm(CannabisVereinType::class, $newVerein);

        $cannabisVereine = $this->entityManager->getRepository(CannabisVerein::class)->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('danger', 'Um einen Verein zu erstellen, musst du dich einloggen.');
                return $this->redirectToRoute('app_login');
            }

            // User who created the BudBash is automatically a participant
            $newVerein->addMitglieder($this->getUser());

            $mapbox_id = $form->get('mapbox_id')->getData();
            $coordinates = $this->cannabisVereinService->getClubCoordinates($mapbox_id);
            $newVerein->setCoordinaten($coordinates['latitude'] . ',' . $coordinates['longitude']);
            $newVerein->setErstelltVon($this->getUser());

            $this->entityManager->persist($newVerein);
            $this->entityManager->flush();

            return $this->redirectToRoute('cannabis_verein');
        }

        return $this->render('cannabis_verein/index.html.twig', [
            'cannabisVereine' => $cannabisVereine,
            'form' => $form->createView(),
        ]);
    }
}
