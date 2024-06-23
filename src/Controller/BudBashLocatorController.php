<?php

namespace App\Controller;

use App\Entity\BudBash;
use App\Entity\BudBashCheckAttendance;
use App\Form\BudBashType;
use App\Services\BudBashLocatorService;
use App\Services\NotificationService;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BudBashLocatorController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BudBashLocatorService $budBashLocatorService,
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly NotificationService $notificationService,
    ) {}

    #[Route('/budbash-locator', name: 'weedwizard_budbash_locator')]
    public function index(Request $request): Response
    {
        $budBashes = $this->entityManager->getRepository(BudBash::class)->findAll();

        // find every party, that is NOT of the user who is currently logged in and
        // that is NOT in the past and filter out where the user is already a participant
        $budBashes = array_filter($budBashes, function ($budBash) {
            return $budBash->getCreatedBy() !== $this->weedWizardKernel->getUser() && $budBash->getStart() > new \DateTime() && !$budBash->getParticipants()->contains($this->weedWizardKernel->getUser());
        });

        // get the lowest and highest entrance fee out of all parties
        if ($budBashes) {
            $lowestEntranceFee = min(array_map(function ($budBash) {
                return $budBash->getEntranceFee();
            }, $budBashes));

            $highestEntranceFee = max(array_map(function ($budBash) {
                return $budBash->getEntranceFee();
            }, $budBashes));
        }

        $newBudBash = new BudBash();
        $form = $this->createForm(BudBashType::class, $newBudBash);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->weedWizardKernel->getUser()) {
                $this->addFlash('danger', 'Um eine Party steigen zu lassen, musst du dich einloggen.');

                return $this->redirectToRoute('app_login');
            }

            // User who created the BudBash is automatically a participant
            $newBudBash->addParticipant($this->weedWizardKernel->getUser());

            if ($form->get('CheckAttendances')->getData()) {
                // User who created the BudBash is automatically checked as attended
                $budBashCheckAttendance = new BudBashCheckAttendance();
                $budBashCheckAttendance->setParticipant($this->weedWizardKernel->getUser());
                $budBashCheckAttendance->setBudBashParty($newBudBash);
                $budBashCheckAttendance->setCheckedAttendance(true);
                $budBashCheckAttendance->setSecretString($this->weedWizardKernel->generateRandomString(20));
                $newBudBash->addBudBashCheckAttendance($budBashCheckAttendance);
            }

            $mapbox_id = $form->get('mapbox_id')->getData();
            $coordinates = $this->budBashLocatorService->getBudBashCoordinates($mapbox_id);
            $newBudBash->setCoordinates($coordinates['latitude'] . ',' . $coordinates['longitude']);
            $newBudBash->setCreatedBy($this->weedWizardKernel->getUser());

            $this->entityManager->persist($newBudBash);
            $this->entityManager->flush();

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        return $this->render('budBash_locator/index.html.twig', [
            'budBashes' => $budBashes,
            'form' => $form->createView(),
            'lowestEntranceFee' => $lowestEntranceFee ?? 0,
            'highestEntranceFee' => $highestEntranceFee ?? 0,
        ]);
    }

    #[Route('/budbash-locator/cancel/{id}', name: 'weedwizard_budbash_locator_cancel_party')]
    public function cancelParty(int $id): Response
    {
        $budBash = $this->entityManager->getRepository(BudBash::class)->find($id);

        if (!$this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Um eine Party abzusagen, musst du dich einloggen.');

            return $this->redirectToRoute('app_login');
        }

        if (!$budBash) {
            $this->addFlash('error', 'Diese Party existiert nicht.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getCreatedBy() !== $this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Du kannst nur deine eigenen Partys absagen.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        foreach ($budBash->getParticipants() as $participant) {
            $this->notificationService->createNotification(
                NotificationService::BUD_BASH_LOCATOR_TYPE,
                'Die Party wurde abgesagt.',
                $participant
            );
        }

        $this->entityManager->remove($budBash);
        $this->entityManager->flush();

        $this->addFlash('success', 'Die Party ' . $budBash->getName() . ' von ' . $budBash->getCreatedBy()->getFirstname() . ' ' . $budBash->getCreatedBy()->getLastname() . ' wurde abgesagt.');

        return $this->redirectToRoute('weedwizard_budbash_locator_my_partys');
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
            return $budBash->getParticipants()->contains($this->weedWizardKernel->getUser()) && $budBash->getStart() > new \DateTime('yesterday') && $budBash->getCreatedBy() !== $this->weedWizardKernel->getUser();
        });

        return $this->render('budBash_locator/attended_partys.html.twig', [
            'budbashes' => $budbashes,
        ]);
    }

    #[Route('/budbash-locator/attend/{id}', name: 'weedwizard_budbash_locator_attend_party')]
    public function attendParty(int $id, Request $request): Response
    {
        $budBash = $this->entityManager->getRepository(BudBash::class)->find($id);

        if (!$this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Um an einer Party teilzunehmen, musst du dich einloggen.');

            return $this->redirectToRoute('app_login');
        }

        if (!$budBash) {
            $this->addFlash('error', 'Diese Party existiert nicht.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getCreatedBy() === $this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Du kannst nicht an deiner eigenen Party teilnehmen.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getStart() < new \DateTime()) {
            $this->addFlash('error', 'Diese Party ist bereits vorbei.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        $budBash->addParticipant($this->weedWizardKernel->getUser());

        if ($budBash->getBudBashCheckAttendances()[0]) {
            $budBashCheckAttendance = new BudBashCheckAttendance();
            $budBashCheckAttendance->setParticipant($this->weedWizardKernel->getUser());
            $budBashCheckAttendance->setBudBashParty($budBash);
            $budBashCheckAttendance->setCheckedAttendance(false);
            $budBashCheckAttendance->setSecretString($this->weedWizardKernel->generateRandomString(20));

            $budBash->addBudBashCheckAttendance($budBashCheckAttendance);
        }

        $this->entityManager->flush();

        $host = $budBash->getCreatedBy();

        $this->notificationService->createNotification(
            NotificationService::BUD_BASH_LOCATOR_TYPE,
            $this->weedWizardKernel->getUser()->getFirstname() . ' ' . $this->weedWizardKernel->getUser()->getLastname() . ' nimmt an deiner Party teil.',
            $host
        );

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

        if (!$this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Um einer Party abzusagen, musst du dich zuerst anmelden.');

            return $this->redirectToRoute('app_login');
        }

        if (!$budBash) {
            $this->addFlash('error', 'Diese Party existiert nicht.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getCreatedBy() === $this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Du kannst dich nicht von deiner eigenen Party abmelden.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        if ($budBash->getStart() < new \DateTime()) {
            $this->addFlash('error', 'Diese Party ist bereits vorbei.');

            return $this->redirectToRoute('weedwizard_budbash_locator');
        }

        $budBash->removeParticipant($this->weedWizardKernel->getUser());

        $this->entityManager->flush();

        $this->addFlash('success', 'Du nimmst nicht mehr an der Party teil!');
        $host = $budBash->getCreatedBy();

        $this->notificationService->createNotification(
            NotificationService::BUD_BASH_LOCATOR_TYPE,
            $this->weedWizardKernel->getUser()->getFirstname() . ' ' . $this->weedWizardKernel->getUser()->getLastname() . ' hat abgesagt.',
            $host
        );

        if ($request->headers->get('referer')) {
            return new RedirectResponse($request->headers->get('referer'));
        }

        return $this->redirectToRoute('weedwizard_budbash_locator');
    }

    #[Route('/budbash-locator/{budbash_id}/checkAttendance/{uid}/{secret_string}', name: 'weedwizard_budbash_locator_check_attendance')]
    public function checkAttendance(int $budbash_id, int $uid, string $secret_string, Request $request): JsonResponse
    {
        if ($budbash_id != $request->get('budBashId')) {
            return new JsonResponse(['error' => 'Der Code ist nicht für diese Party.'], Response::HTTP_BAD_REQUEST);
        }

        $budBash = $this->entityManager->getRepository(BudBash::class)->find($budbash_id);

        if (!$budBash) {
            return new JsonResponse(['error' => 'Diese Party existiert nicht.'], Response::HTTP_NOT_FOUND);
        }

        $budBashCheckAttendance = $this->entityManager->getRepository(BudBashCheckAttendance::class)->findOneBy(['participant' => $uid, 'BudBashParty' => $budBash]);

        if (!$budBashCheckAttendance) {
            return new JsonResponse(['error' => 'User ist nicht als Teilnehmer dieser Party eingetragen.'], Response::HTTP_FORBIDDEN);
        }

        if ($budBashCheckAttendance->getSecretString() !== $secret_string) {
            return new JsonResponse(['error' => 'Secret String stimmt nicht überein. Zutritt verweigert.'], Response::HTTP_FORBIDDEN);
        }

        $budBashCheckAttendance->setCheckedAttendance(true);
        $this->entityManager->persist($budBashCheckAttendance);
        $this->entityManager->flush();

        return new JsonResponse(['success' => 'Willkommen ' . $budBashCheckAttendance->getParticipant()->getFirstname() . ' ' . $budBashCheckAttendance->getParticipant()->getLastname() . ' auf der Party. Viel Spaß!'], Response::HTTP_OK);
    }
}
