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
    private SeedFinderApiService $seedfinderApiService;

    public function __construct(SeedFinderApiService $seedfinderApiService)
    {
        $this->seedfinderApiService = $seedfinderApiService;
    }

    #[Route('/cannastrain-library', name: 'weedwizard_cannastrain-library')]
    public function index(): Response
    {
        $this->seedfinderApiService->getJsonTestData();

        try {
            $strains = $this->seedfinderApiService->getMostPopularStrains();
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return new Response('Error: ' . $e->getMessage());
        } catch (Exception $e) {
            return new Response('Error: ' . $e->getMessage());
        }

        return $this->render('cannastrain_library/index.html.twig', [
            'filters' => $this->filters,
            'strains' => $strains,
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
