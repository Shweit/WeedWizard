<?php

namespace App\Controller\API;

use App\Entity\BudBash;
use App\Entity\CannabisVerein;
use App\Entity\MapMarkers;
use App\Form\AddMarkerFormType;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ComplianceMapApiController extends AbstractController
{
    public function __construct(
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/api/compliance-map/add-marker', name: 'weedwizard_compliance_map_add_marker', methods: ['POST'])]
    public function addMarker(Request $request): Response
    {
        if (!$this->weedWizardKernel->getUser()) {
            return new JsonResponse([
                'error' => 'Du musst angemeldet sein, um einen Marker hinzufügen zu können.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // TODO: Add limit for not Premium users
        $markers = $this->entityManager->getRepository(MapMarkers::class)->findBy([
            'user' => $this->weedWizardKernel->getUser(),
        ]);

        if (count($markers) >= 3 && !$this->weedWizardKernel->isUserPremium()) {
            return new JsonResponse([
                'error' => 'Du hast bereits 3 Marker hinzugefügt. Das ist das Limit für nicht Premium Nutzer.',
            ], Response::HTTP_FORBIDDEN);
        }

        $addMarkerForm = $this->createForm(AddMarkerFormType::class);
        $addMarkerForm->handleRequest($request);

        if ($addMarkerForm->isSubmitted() && $addMarkerForm->isValid()) {
            $marker = $addMarkerForm->getData();
            $marker->setCoordinates($request->get('add_marker_form')['coordinates']); // For some only God knows why reason, the coordinates are not being set by the form
            $marker->setUser($this->getUser());
            $this->entityManager->persist($marker);
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => 'Marker wurde hinzugefügt.',
                'marker' => [
                    'id' => $marker->getId(),
                    'title' => $marker->getTitle(),
                    'description' => $marker->getDescription(),
                    'coordinates' => $marker->getCoordinates(),
                ],
            ]);
        }

        return new JsonResponse([
            'error' => 'Formular ist nicht valide. ' . $addMarkerForm->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/api/compliance-map/del-marker/{id}', name: 'weedwizard_compliance_map_del_marker', methods: ['GET'])]
    public function delMarker(int $id): Response
    {
        $marker = $this->entityManager->getRepository(MapMarkers::class)->find($id);

        if (!$this->weedWizardKernel->getUser()) {
            return new JsonResponse([
                'error' => 'Du musst angemeldet sein, um den Marker zu löschen.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$marker) {
            return new JsonResponse([
                'error' => 'Marker nicht gefunden.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($marker->getUser() !== $this->weedWizardKernel->getUser()) {
            return new JsonResponse([
                'error' => 'Du kannst nur deine eigenen Marker löschen.',
            ], Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($marker);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => 'Marker wurde gelöscht.',
        ]);
    }

    #[Route('/api/compliance-map/get-markers', name: 'weedwizard_compliance_map_get_markers', methods: ['GET'])]
    public function getMarkers(): Response
    {
        if (!$this->weedWizardKernel->getUser()) {
            return new JsonResponse([
                'error' => 'User not logged in',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $markers = $this->entityManager->getRepository(MapMarkers::class)->findBy([
            'user' => $this->weedWizardKernel->getUser(),
        ]);

        $markers = array_map(function (MapMarkers $marker) {
            return [
                'id' => $marker->getId(),
                'title' => $marker->getTitle(),
                'description' => $marker->getDescription(),
                'coordinates' => $marker->getCoordinates(),
            ];
        }, $markers);

        return new JsonResponse([
            'markers' => $markers,
        ]);
    }

    #[Route('/api/compliance-map/get-bud-bashes', name: 'weedwizard_compliance_map_get_bud_bashes', methods: ['GET'])]
    public function getBudBashes(): Response
    {
        $budBashes = $this->entityManager->getRepository(BudBash::class)->findAll();

        $budBashes = array_filter($budBashes, function ($budBash) {
            return $budBash->getStart() > new \DateTime();
        });

        $budBashes = array_map(function (BudBash $budBash) {
            return [
                'id' => $budBash->getId(),
                'name' => $budBash->getName(),
                'start' => $budBash->getStart()->format('d.m.Y H:i:s'),
                'coordinates' => $budBash->getCoordinates(),
                'entrance_fee' => $budBash->getEntranceFee(),
                'extraInfo' => $budBash->getExtraInfo() ?? 'Der ersteller der Party hat keine Informationen angegeben.',
                'address' => $budBash->getAddress() ?? '',
            ];
        }, $budBashes);

        return new JsonResponse([
            'markers' => $budBashes,
        ]);
    }

    #[Route('/api/compliance-map/get-public-markers', name: 'weedwizard_compliance_map_get_public_markers', methods: ['GET'])]
    public function getPublicMarkers(): Response
    {
        $markers = $this->entityManager->getRepository(MapMarkers::class)->findBy([
            'public' => true,
        ]);

        if ($this->weedWizardKernel->getUser()) {
            $markers = array_filter($markers, function (MapMarkers $marker) {
                return $marker->getUser() !== $this->weedWizardKernel->getUser();
            });
        }

        $markers = array_map(function (MapMarkers $marker) {
            return [
                'id' => $marker->getId(),
                'title' => $marker->getTitle(),
                'description' => $marker->getDescription(),
                'coordinates' => $marker->getCoordinates(),
                'name' => $marker->getUser()->getFirstname() . ' ' . $marker->getUser()->getLastname(),
            ];
        }, $markers);

        return new JsonResponse([
            'markers' => $markers,
        ]);
    }

    #[Route('/api/compliance-map/get-clubs', name: 'weedwizard_compliance_map_get_clubs', methods: ['GET'])]
    public function getClubs(): Response
    {
        $club = $this->entityManager->getRepository(CannabisVerein::class)->findAll();

        $club = array_map(function (CannabisVerein $club) {
            return [
                'id' => $club->getId(),
                'name' => $club->getName(),
                'description' => $club->getBeschreibung(),
                'coordinates' => $club->getCoordinaten(),
                'fee' => $club->getMitgliedsbeitrag(),
                'address' => $club->getAdresse(),
                'website' => $club->getWebsite(),
            ];
        }, $club);

        return new JsonResponse([
            'clubs' => $club,
        ]);
    }

    #[Route('/api/compliance-map/get-public-markers', name: 'weedwizard_compliance_map_get_public_markers', methods: ['GET'])]
    public function getPublicMarkers(): Response
    {
        $markers = $this->entityManager->getRepository(MapMarkers::class)->findBy([
            'public' => true,
        ]);

        if ($this->weedWizardKernel->getUser()) {
            $markers = array_filter($markers, function (MapMarkers $marker) {
                return $marker->getUser() !== $this->weedWizardKernel->getUser();
            });
        }

        $markers = array_map(function (MapMarkers $marker) {
            return [
                'id' => $marker->getId(),
                'title' => $marker->getTitle(),
                'description' => $marker->getDescription(),
                'coordinates' => $marker->getCoordinates(),
                'name' => $marker->getUser()->getFirstname() . ' ' . $marker->getUser()->getLastname(),
            ];
        }, $markers);

        return new JsonResponse([
            'markers' => $markers,
        ]);
    }
}
