<?php

namespace App\Controller;

use App\Service\SeedFinderApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannaStrainLibraryController extends AbstractController
{
    private SeedFinderApiService $seedFinderApiService;
    private array $breederFilters = [
        'searchForNameAllowed' => true,
        'extendedFilters' => false,
    ];
    private array $strainFilters = [
        'searchForNameAllowed' => false,
        'extendedFilters' => true,
    ];

    private array $noFilters = [
        'searchForNameAllowed' => false,
        'extendedFilters' => false,
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
            'breeder_id' => $breeder['seedfinder_id'],
        ]);
    }

    #[Route('/cannastrain-library/{breeder_id}/{strain_id}', name: 'weedwizard_cannastrain-library_strain-view')]
    public function showStrain(string $breeder_id, string $strain_id): Response
    {
        $breeder = $this->seedFinderApiService->getBreederInfo($breeder_id);
        $strain = $this->seedFinderApiService->getStrainInfo($breeder_id, $strain_id);

        // Other strains links
        $previousStrain = $this->findPreviousStrain($breeder['strains'], $strain_id);
        $nextStrain = $this->findNextStrain($breeder['strains'], $strain_id);

        // Selected strain info
        $strainName = $strain['name'];
        $strainInfo = $strain['breeder_info'];
        $strainMedicalInfo = $strain['medical'];

        return $this->render('cannastrain_library/strain/showStrain.html.twig', [
            'filters' => $this->noFilters,
            'strainInfo' => $strainInfo,
            'strainName' => $strainName,
            'strainMedicalInfo' => $strainMedicalInfo,
            'strain' => $strain,
            'breeder' => $breeder,
            'previousStrainLink' => $this->generateBtnLinkToStrain($breeder_id, $previousStrain['seedfinder_id']),
            'nextStrainLink' => $this->generateBtnLinkToStrain($breeder_id, $nextStrain['seedfinder_id']),
        ]);
    }

    private function findPreviousStrain($allStrainsFromBreeder, string $currentStrainId)
    {
        $allStrainIds = array_column($allStrainsFromBreeder, 'seedfinder_id');
        $currentIndex = array_search($currentStrainId, $allStrainIds);

        // Return last element if current strain is first or not found
        if ($currentIndex === false || $currentIndex === 0) {
            return end($allStrainsFromBreeder);
        }

        return $allStrainsFromBreeder[$currentIndex - 1];
    }

    private function findNextStrain($allStrainsFromBreeder, string $currentStrainId)
    {
        $allStrainIds = array_column($allStrainsFromBreeder, 'seedfinder_id');
        $currentIndex = array_search($currentStrainId, $allStrainIds);

        // Return first element if current strain is last or not found
        if ($currentIndex === false || $currentIndex === count($allStrainIds) - 1) {
            return reset($allStrainsFromBreeder);
        }

        return $allStrainsFromBreeder[$currentIndex + 1];
    }

    private function generateBtnLinkToStrain($breederId, $strainId): string
    {
        return $this->generateUrl('weedwizard_cannastrain-library_strain-view', [
            'breeder_id' => $breederId,
            'strain_id' => $strainId,
        ]);
    }
}
