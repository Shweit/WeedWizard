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

    private int $paginationLimit = 8;

    public function __construct(SeedFinderApiService $seedFinderApiService)
    {
        $this->seedFinderApiService = $seedFinderApiService;
    }

    #[Route('/cannastrain-library/page={page}', name: 'weedwizard_cannastrain-library')]
    public function index(int $page): Response
    {
        $breeders = $this->seedFinderApiService->getBreederInfo();
        $totalNumOfBreeders = count($this->seedFinderApiService->getBreederInfoPaginated('all', 1, PHP_INT_MAX));

        // $displayedBreeders = applySelectedFilters($breeders); // TODO: Remove if unnecessary

        return $this->render('cannastrain_library/index.html.twig', [
            'breeders' => $breeders,
            'filters' => $this->breederFilters,
            'currentPage' => $page,
            'totalPages' => ceil($totalNumOfBreeders / $this->paginationLimit),
            'breeder_id' => null,
        ]);
    }

    #[Route('/cannastrain-library/{breeder_id}/page={page}', name: 'weedwizard_cannastrain-library_breeder-view')]
    public function showBreeder(string $breeder_id, int $page): Response
    {
        $breeder = $this->seedFinderApiService->getBreederInfoPaginated($breeder_id, $page, $this->paginationLimit);
        $totalNumOfStrains = count($breeder['strains']);

        return $this->render('cannastrain_library/breeder/showBreeder.html.twig', [
            'breeder' => $breeder,
            'filters' => $this->strainFilters,
            'strains' => $breeder['strains'],
            'currentPage' => $page,
            'totalPages' => ceil($totalNumOfStrains / $this->paginationLimit),
            'breeder_id' => $breeder['seedfinder_id'],
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
            'filters' => $this->noFilters,
            'strainInfo' => $strainInfo,
            'strainName' => $strainName,
            'strainMedicalInfo' => $strainMedicalInfo,
            'strain' => $strain,
            'breeder' => $breeder,
        ]);
    }
}
