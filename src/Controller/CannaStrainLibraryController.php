<?php

namespace App\Controller;

use App\Service\SeedFinderApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannaStrainLibraryController extends AbstractController
{
    private SeedFinderApiService $seedFinderApiService;
    private array $breederFilters = [];
    private array $strainFilters = [
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

    public function __construct(SeedFinderApiService $seedFinderApiService)
    {
        $this->seedFinderApiService = $seedFinderApiService;
    }

    #[Route('/cannastrain-library', name: 'weedwizard_cannastrain-library')]
    public function index(): Response
    {
        $breeders = $this->seedFinderApiService->getBreederInfo();

        return $this->render('cannastrain_library/index.html.twig', [
            'breeders' => $breeders,
            'filters' => $this->breederFilters,
        ]);
    }

    #[Route('/cannastrain-library/{breeder_id}', name: 'weedwizard_cannastrain-library_breeder-view')]
    public function showBreeder(string $breeder_id): Response
    {
        $breeder = $this->seedFinderApiService->getBreederInfo($breeder_id);

        return $this->render('cannastrain_library/breeder/showBreeder.html.twig', [
            'breeder' => $breeder,
            'filters' => $this->strainFilters,
            'strains' => $breeder['strains'],
        ]);
    }

    #[Route('/cannastrain-library/{breeder_id}/{strain_id}', name: 'weedwizard_cannastrain-library_strain-view')]
    public function showStrain(string $breeder_id, string $strain_id): Response
    {
        $breeder = $this->seedFinderApiService->getBreederInfo($breeder_id);
        $strain = $this->seedFinderApiService->getStrainInfo($breeder_id, $strain_id);

        $strainName = $strain['name'];
        $strainInfo = $strain['breeder_info'];
        $strainMedicalInfo = $strain['medical'];

        return $this->render('cannastrain_library/strain/showStrain.html.twig', [
            'filters' => [],
            'strainInfo' => $strainInfo,
            'strainName' => $strainName,
            'strainMedicalInfo' => $strainMedicalInfo,
            'strain' => $strain,
            'breeder' => $breeder,
        ]);
    }
}
