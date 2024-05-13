<?php

namespace App\Controller;

use App\Entity\CannabisVerein;
use App\Entity\User;
use App\Form\CannabisVereinType;
use App\Services\CannabisVereinService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannabisVereinController extends AbstractController
{
    private User $user;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CannabisVereinService $cannabisVereinService,
    ) {}

    #[Route('/cannabis-verein', name: 'cannabis_verein')]
    public function index(Request $request): Response
    {
        $cannabisVereine = $this->entityManager->getRepository(CannabisVerein::class)->findAll();

        $cannabisVereine = array_filter($cannabisVereine, function ($verein) {
            return $verein->getErstelltVon() !== $this->getUser() && !$verein->getMitglieder()->contains($this->getUser());
        });

        $newVerein = new CannabisVerein();
        $form = $this->createForm(CannabisVereinType::class, $newVerein);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('danger', 'Um einen Verein zu erstellen, musst du dich einloggen.');

                return $this->redirectToRoute('app_login');
            }

            $this->user = $this->entityManager->getRepository(User::class)->findByEmail($this->getUser()->getUserIdentifier());

            $newVerein->addMitglieder($this->user);
            $newVerein->setErstelltVon($this->user);

            // TODO: Get coordinates from mapbox ask @Shweit
            $mapbox_id = $form->get('mapbox_id')->getData();
            $coordinates = $this->cannabisVereinService->getClubCoordinates($mapbox_id);
            $newVerein->setCoordinaten($coordinates['latitude'] . ',' . $coordinates['longitude']);

            $this->entityManager->persist($newVerein);
            $this->entityManager->flush();

            return $this->redirectToRoute('cannabis_verein');
        }

        return $this->render('cannabis_verein/index.html.twig', [
            'cannabisVereine' => $cannabisVereine,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/join-verein/{id}', name: 'join_verein')]
    public function show(int $id): Response
    {
        $cannabisVerein = $this->entityManager->getRepository(CannabisVerein::class)->find($id);
        if (!$cannabisVerein) {
            $this->addFlash('danger', 'Verein nicht gefunden.');

            return $this->redirectToRoute('cannabis_verein');
        }

        if (!$this->getUser()) {
            $this->addFlash('danger', 'Um einem Verein beizutreten, musst du dich einloggen.');

            return $this->redirectToRoute('app_login');
        }

        $this->user = $this->entityManager->getRepository(User::class)->findByEmail($this->getUser()->getUserIdentifier());
        if ($this->user->getCannabisVereine()->count() > 0) {
            $this->addFlash('danger', 'Du kannst nur Mitglied in einem Verein sein.');

            return $this->redirectToRoute('cannabis_verein');
        }

        if ($cannabisVerein->getMitglieder()->contains($this->user) || $cannabisVerein->getErstelltVon() === $this->user) {
            $this->addFlash('danger', 'Du bist bereits Mitglied in diesem Verein.');

            return $this->redirectToRoute('cannabis_verein');
        }

        $cannabisVerein->addMitglieder($this->user);
        $this->entityManager->flush();

        return $this->redirectToRoute('cannabis_verein');
    }
}
