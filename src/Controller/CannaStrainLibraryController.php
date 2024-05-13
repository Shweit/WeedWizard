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

    #[Route('/cannastrainlibrary', name: 'weedwizard_cannastrainlibrary')]
    public function index(): Response
    {
        return $this->render('canna_strain_library/index.html.twig', [
            'controller_name' => 'CannaStrainLibraryController',
            'filters' => $this->filters,
        ]);
    }
}
