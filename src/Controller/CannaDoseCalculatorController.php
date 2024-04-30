<?php

namespace App\Controller;

use App\Entity\CannaDoseCalculator;
use App\Form\CannaDoseCalculatorType;
use App\Services\CannaDoseCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CannaDoseCalculatorController extends AbstractController
{
    public function __construct(
        private readonly CannaDoseCalculatorService $cannaDoseCalculatorService,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/cannadose-calculator', name: 'weedwizard_cannadose_calculator')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CannaDoseCalculatorType::class, null, [
            'action' => $this->generateUrl('weedwizard_cannadose_calculator'),
        ]);

        $recommendedDosages = $this->entityManager->getRepository(CannaDoseCalculator::class)->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($this->validPayload($data) === false) {
                $this->addFlash('error', 'Ungültiger Payload. Bitte versuchen Sie es erneut.');

                return $this->redirectToRoute('weedwizard_cannadose_calculator');
            }
            $dose = $this->cannaDoseCalculatorService->calculateDose($data);

            $cannaDoseCalculator = new CannaDoseCalculator();
            $cannaDoseCalculator->setExperience($data['experience']);
            $cannaDoseCalculator->setIntensity($data['intensity']);
            $cannaDoseCalculator->setBasisDosage($data['basis_dosage']);
            $cannaDoseCalculator->setRecommendedDosage($dose);

            $this->entityManager->persist($cannaDoseCalculator);
            $this->entityManager->flush();

            return $this->redirectToRoute('weedwizard_cannadose_calculator_show_recommended_dosage', ['id' => $cannaDoseCalculator->getId()]);
        }

        return $this->render('canna_dose_calculator/index.html.twig', [
            'form' => $form,
            'recommendedDosages' => $recommendedDosages,
        ]);
    }

    #[Route('/cannadose-calculator/{id}', name: 'weedwizard_cannadose_calculator_show_recommended_dosage')]
    public function showRecommendedDosage(int $id): Response
    {
        $lastDosage = $this->entityManager->getRepository(CannaDoseCalculator::class)->find($id);
        $chart = $this->cannaDoseCalculatorService->calculateRangeIntensityChart($lastDosage);

        return $this->render('canna_dose_calculator/show_recommended_dosage.html.twig', [
            'lastDosage' => $lastDosage,
            'chart' => $chart,
        ]);
    }

    private function validPayload(array $data): bool
    {
        // Checks if there is a not allowed parameter in the payload
        $allowedKeys = ['experience', 'basis_dosage', 'intensity'];

        foreach ($data as $key => $value) {
            if (!in_array($key, $allowedKeys)) {
                return false;
            }
        }

        return true;
    }
}
