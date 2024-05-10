<?php

namespace App\Controller;

use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Form\BudBashType;
use App\Services\BudBashLocatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BudBashLocatorController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BudBashLocatorService  $budBashLocatorService,
    )
    {}

    #[Route('/budbash-locator', name: 'weedwizard_budbash_locator')]
    public function index(Request $request): Response
    {
        $budBashes = $this->entityManager->getRepository(BudBash::class)->findAll();

        // find every party, that is NOT of the user who is currently logged in and
        // that is NOT in the past and filter out where the user is already a participant
        $budBashes = array_filter($budBashes, function ($budBash) {
            return $budBash->getCreatedBy() !== $this->getUser() && $budBash->getStart() > new \DateTime() && !$budBash->getParticipants()->contains($this->getUser());
        });

        $newBudBash = new BudBash();
        $form = $this->createForm(BudBashType::class, $newBudBash);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('danger', 'Um eine Party steigen zu lassen, musst du dich einloggen.');
                return $this->redirectToRoute('app_login');
            }

            // User who created the BudBash is automatically a participant
            $newBudBash->addParticipant($this->getUser());

            if ($form->get('CheckAttendances')->getData()) {
                // User who created the BudBash is automatically checked as attended
                $budBashCheckAttendance = new BudBashCheckAttendance();
                $budBashCheckAttendance->setParticipant($this->getUser());
                $budBashCheckAttendance->setBudBashParty($newBudBash);
                $budBashCheckAttendance->setCheckedAttendance(true);
                $newBudBash->addBudBashCheckAttendance($budBashCheckAttendance);
            }

            $mapbox_id = $form->get('mapbox_id')->getData();
            $coordinates = $this->budBashLocatorService->getBudBashCoordinates($mapbox_id);
            $newBudBash->setCoordinates($coordinates['latitude'] . ',' . $coordinates['longitude']);
            $newBudBash->setCreatedBy($this->getUser());

            $this->entityManager->persist($newBudBash);
            $this->entityManager->flush();

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        return $this->render('budBash_locator/index.html.twig', [
            'budBashes' => $budBashes,
            'form' => $form->createView(),
        ]);
    }

   #[Route('/budbash-locator/my-partys', name: 'weedwizard_budbash_locator_my_partys')]
    public function myPartys(): Response
    {
        return $this->render('budBash_locator/my_partys.html.twig');
    }

   #[Route('/budbash-locator/attended-partys', name: 'weedwizard_budbash_locator_attended_partys')]
    public function attendedPartys(): Response
    {
        $budbashes = $this->entityManager->getRepository(BudBash::class)->findAll();
        // find every party, where the user is a participant and which is NOT in the past
        // and where the user is NOT the creator of the party
        $budbashes = array_filter($budbashes, function ($budBash) {
            return $budBash->getParticipants()->contains($this->getUser()) && $budBash->getStart() > new \DateTime() && $budBash->getCreatedBy() !== $this->getUser();
        });

        return $this->render('budBash_locator/attended_partys.html.twig', [
            'budbashes' => $budbashes,
        ]);
    }


    #[Route('/budbash-locator/attend/{id}', name: 'weedwizard_budbash_locator_attend_party')]
    public function attendParty(int $id, Request $request): Response
    {
        $budBash = $this->entityManager->getRepository(BudBash::class)->find($id);

        if (!$this->getUser()) {
            $this->addFlash('error', 'Um an einer Party teilzunehmen, musst du dich einloggen.');
            return $this->redirectToRoute('app_login');
        }

        if (!$budBash) {
            $this->addFlash('error', 'Diese Party existiert nicht.');
            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getCreatedBy() === $this->getUser()) {
            $this->addFlash('error', 'Du kannst nicht an deiner eigenen Party teilnehmen.');
            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getStart() < new \DateTime()) {
            $this->addFlash('error', 'Diese Party ist bereits vorbei.');
            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        $budBash->addParticipant($this->getUser());

        $this->entityManager->flush();

        $this->addFlash('success', 'Du nimmst an der Party teil!');

        if ($request->headers->get('referer')) {
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->redirectToRoute('weedwizard_budbash_locator');
    }

    #[Route('/budbash-locator/absence/{id}', name: 'weedwizard_budbash_locator_absence_party')]
    public function absenceParty(int $id, Request $request)
    {
        $budBash = $this->entityManager->getRepository(BudBash::class)->find($id);

        if (!$this->getUser()) {
            $this->addFlash('error', 'Um einer Party abzusagen, musst du dich zuerst anmelden.');
            return $this->redirectToRoute('app_login');
        }

        if (!$budBash) {
            $this->addFlash('error', 'Diese Party existiert nicht.');
            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getCreatedBy() === $this->getUser()) {
            $this->addFlash('error', 'Du kannst dich nicht von deiner eigenen Party abmelden.');
            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getStart() < new \DateTime()) {
            $this->addFlash('error', 'Diese Party ist bereits vorbei.');
            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        $budBash->removeParticipant($this->getUser());

        $this->entityManager->flush();

        $this->addFlash('success', 'Du nimmst nicht mehr an der Party teil!');

        if ($request->headers->get('referer')) {
            return new RedirectResponse($request->headers->get('referer'));
        }
        return $this->redirectToRoute('weedwizard_budbash_locator');

    }
}
