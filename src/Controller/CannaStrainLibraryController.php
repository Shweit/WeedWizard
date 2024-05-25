<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannaStrainLibraryController extends AbstractController
{
    protected array $filters = [
        'filter' => [
            'Herkunft' => [
                'indica',
                'sativa',
                'ruderalis',
                'unbekannt'],
            'Strain-Typ' => [
                'nur nicht femisierte',
                'femisierte',
                'close-only strains'],
            'Location' => [
                'Indoor',
                'Outdoor',
                'Gewächshaus'],
        ],
    ];

    #[Route('/cannastrain-library', name: 'weedwizard_cannastrain-library')]
    public function index(): Response
    {
        return $this->render('cannastrain_library/index.html.twig', [
            'filters' => $this->filters,
        ]);
    }

    #[Route('/cannastrain-library/{id}', name: 'weedwizard_cannastrain-library_detailview')]
    public function show(int $id): Response
    {
        $id = 1;

        return $this->render('cannastrain_library/show.html.twig', [
            'id' => $id,
            'filters' => $this->filters,
        ]);
    }
}
