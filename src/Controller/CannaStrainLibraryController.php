<?php

namespace App\Controller;

use App\Service\SeedFinderApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use function Symfony\Component\String\s;

class CannaStrainLibraryController extends AbstractController
{
    private SeedFinderApiService $seedFinderApiService;
    private $breeders;
    private array $breederFilters = [];
    private $strains;
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
        try {
            $this->breeders = $this->seedFinderApiService->getBreederInfo(false);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return new Response('Error: ' . $e->getMessage());
        } catch (Exception $e) {
            return new Response('Error: ' . $e->getMessage());
        }

        return $this->render('cannastrain_library/index.html.twig', [
            'breeders' => $this->breeders,
            'filters' => $this->breederFilters,
        ]);
    }

    #[Route('/cannastrain-library/breeder/{breederName}', name: 'weedwizard_cannastrain-library_breeder-view')]
    public function showBreeder(string $breederName): Response
    {
        try {
            $this->strains = $this->seedFinderApiService->getStrainsByBreeder($breederName);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return new Response('Error: ' . $e->getMessage());
        } catch (Exception $e) {
            return new Response('Error: ' . $e->getMessage());
        }

        $this->strains = $this->strains[str_replace(' ', '_', $breederName)]['strains'];

        return $this->render('cannastrain_library/breeder/showBreeder.html.twig', [
            'breederName' => $breederName,
            'filters' => $this->strainFilters,
            'strains' => $this->strains,
        ]);
    }

    #[Route('/cannastrain-library/strain/{breederName}/{strainName}', name: 'weedwizard_cannastrain-library_strain-view')]
    public function showStrain(string $breederName, string $strainName): Response
    {
        $strain = $this->seedFinderApiService->getStrainInfo($breederName, $strainName);

        $strainName = $strain['name'];
        $strainInfo = $strain['brinfo'];
        $strainMedicalInfo = $strain['medical'];

        return $this->render('cannastrain_library/strain/showStrain.html.twig', [
            'filters' => [],
            'strainInfo' => $strainInfo,
            'strainName' => $strainName,
            'strainMedicalInfo' => $strainMedicalInfo,
        ]);
    }
}