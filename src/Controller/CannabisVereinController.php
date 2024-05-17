<?php

namespace App\Controller;

use App\Entity\CannabisVerein;
use App\Form\CannabisVereinType;
use App\Services\CannabisVereinService;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannabisVereinController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CannabisVereinService $cannabisVereinService,
        private readonly WeedWizardKernel $weedWizardKernel,
    ) {}

    #[Route('/cannabis-verein', name: 'cannabis_verein')]
    public function index(Request $request): Response
    {
        $cannabisVereine = $this->entityManager->getRepository(CannabisVerein::class)->findAll();

        $cannabisVereine = array_filter($cannabisVereine, function ($verein) {
            return $verein->getErstelltVon() !== $this->weedWizardKernel->getUser()
                && !$verein->getMitglieder()->contains($this->weedWizardKernel->getUser());
        });

        $lowestPrice = round(min(array_map(function ($verein) {
            return $verein->getMitgliedsbeitrag();
        }, $cannabisVereine)) - 1);

        $highestPrice = round(max(array_map(function ($verein) {
            return $verein->getMitgliedsbeitrag();
        }, $cannabisVereine)) + 1);

        $newVerein = new CannabisVerein();
        $form = $this->createForm(CannabisVereinType::class, $newVerein);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('danger', 'Um einen Verein zu erstellen, musst du dich einloggen.');

                return $this->redirectToRoute('app_login');
            }

            if ($this->weedWizardKernel->getUser()->getCannabisVereine()->count() > 0) {
                $this->addFlash('danger', 'Du kannst nur Mitglied in einem Verein sein.');

                return $this->redirectToRoute('cannabis_verein');
            }

            $newVerein->addMitglieder($this->weedWizardKernel->getUser());
            $newVerein->setErstelltVon($this->weedWizardKernel->getUser());

            $mapbox_id = $form->get('mapbox_id')->getData();
            $coordinates = $this->cannabisVereinService->getClubCoordinates($mapbox_id);
            $newVerein->setCoordinaten($coordinates['latitude'] . ',' . $coordinates['longitude']);

            $this->entityManager->persist($newVerein);
            $this->entityManager->flush();

            return $this->redirectToRoute('cannabis_verein');
        }

        return $this->render('cannabis_verein/index.html.twig', [
            'cannabisVereine' => $cannabisVereine,
            'lowestPrice' => $lowestPrice,
            'highestPrice' => $highestPrice,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cannabis-verein/join/{id}', name: 'join_verein')]
    public function joinClub(int $id): Response
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

        if ($this->weedWizardKernel->getUser()->getCannabisVereine()->count() > 0) {
            $this->addFlash('danger', 'Du kannst nur Mitglied in einem Verein sein.');

            return $this->redirectToRoute('cannabis_verein');
        }

        if ($cannabisVerein->getMitglieder()->contains($this->weedWizardKernel->getUser()) || $cannabisVerein->getErstelltVon() === $this->weedWizardKernel->getUser()) {
            $this->addFlash('danger', 'Du bist bereits Mitglied in diesem Verein.');

            return $this->redirectToRoute('cannabis_verein');
        }

        $cannabisVerein->addMitglieder($this->weedWizardKernel->getUser());
        $this->entityManager->flush();

        return $this->redirectToRoute('my_club');
    }

    #[Route('/cannabis-verein/my-club', name: 'my_club')]
    public function myClub(): Response
    {
        return $this->render('cannabis_verein/_my_club.html.twig');
    }

    #[Route('/cannabis-verein/leave/{id}', name: 'leave_verein')]
    public function leaveClub(int $id): Response
    {
        $verein = $this->entityManager->getRepository(CannabisVerein::class)->find($id);
        $user = $this->weedWizardKernel->getUser();

        if ($verein->getMitglieder()->count() === 1) {
            $user->removeCannabisVereine($verein);
            $user->removeErstellteVereine($verein);
            $this->entityManager->remove($verein);
        } else {
            $verein->removeMitglieder($this->weedWizardKernel->getUser());
            $user->getCannabisVereine()->first()->removeMitglieder($this->weedWizardKernel->getUser());
        }
        $this->entityManager->flush();

        return $this->redirectToRoute('cannabis_verein');
    }
}
