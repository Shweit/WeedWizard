<?php

namespace App\Controller;

use App\Entity\MapMarkers;
use App\Form\AddMarkerFormType;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ComplianceMapController extends AbstractController
{
    public function __construct(
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/compliance-map', name: 'weedwizard_compliance_map')]
    public function index(): Response
    {
        $addMarkerForm = $this->createForm(AddMarkerFormType::class);

        return $this->render('compliance_map/index.html.twig', [
            'controller_name' => 'ComplianceMapController',
            'addMarkerForm' => $addMarkerForm->createView(),
        ]);
    }

    #[Route('/compliance-map/add-marker', name: 'weedwizard_compliance_map_add_marker', methods: ['POST'])]
    public function addMarker(Request $request): Response
    {
        if (!$this->weedWizardKernel->getUser()) {
            return new JsonResponse([
                'error' => 'Du musst angemeldet sein, um einen Marker hinzufügen zu können.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // TODO: Add limit for not Premium users

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

    #[Route('/compliance-map/del-marker/{id}', name: 'weedwizard_compliance_map_del_marker', methods: ['GET'])]
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

    #[Route('/compliance-map/get-markers', name: 'weedwizard_compliance_map_get_markers', methods: ['GET'])]
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
}
