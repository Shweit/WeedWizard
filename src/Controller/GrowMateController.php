<?php

// src/Controller/GrowMateController.php

namespace App\Controller;

use App\Entity\Breeder;
use App\Entity\Plant;
use App\Entity\Strain;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use App\Service\GrowMateService;
use App\Services\CannaConsultantServiceV2;
use App\Services\NotificationService;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrowMateController extends AbstractController
{
    public function __construct(
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly CannaConsultantServiceV2 $cannaConsultantService,
        private readonly GrowMateService $growMateService,
        private readonly ChartBuilderInterface $chartBuilder

    ) {}

    #[Route('/grow-mate', name: 'growMate')]
    public function index(Request $request, EntityManagerInterface $entityManager, PlantRepository $plantRepository): Response
    {

        if (!$this->weedWizardKernel->isUserPremium()) {
            $this->addFlash('error', 'Du brauchst ein Premium-Abo um den GrowMate zu nutzen');

            return $this->redirectToRoute('weedwizard_premium');
        }

        $user = $this->weedWizardKernel->getUser();
        $plants = $entityManager->getRepository(Plant::class)->findBy(['user' => $user]);

        $plants = array_map(function (Plant $plant) {
            return [
                'id' => $plant->getId(),
                'name' => $plant->getName(),
                'date' => $plant->getDate(),
                'state' => $plant->getState(),
                'placeOfCultivation' => $plant->getPlaceOfCultivation(),
                'lighting' => $plant->getLighting(),
                'breeder' => $plant->getBreeder(),
                'strain' => $plant->getStrain(),
                'growth' => $plant->getGrowth(),
                'thread' => $plant->getThread(),
                'messages' => $plant->getThread() ? $this->cannaConsultantService->getRecentMessages($plant->getThread()) : '',
                'chart' => $this->growMateService->calculateRangeIntensityChart($plant),
                'currentPrognosisValue' => $plant->getCurrentPrognosisValue(),
            ];
        }, $plants);

        $form = $this->createForm(PlantType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->weedWizardKernel->getUser()) {
                $this->addFlash('error', 'Du musst eingeloggt sein um eine Pflanze erstellen zu können.');

                return $this->redirectToRoute('growMate');
            }

            if (count($plants) >= 3) {
                $this->addFlash('error', 'Du kannst nur bis zu 3 Pflanzen hinzufügen.');

                return $this->redirectToRoute('growMate');
            }

            $plant = new Plant();
            $plant->setName($form->get('name')->getData());
            $plant->setDate($form->get('date')->getData());
            $plant->setState($form->get('state')->getData());
            $plant->setPlaceOfCultivation($form->get('placeOfCultivation')->getData());

            switch ($plant->getPlaceOfCultivation()) {
                case 'indoor':
                    $plant->setLighting('lamp');

                    break;
                case 'outdoor':
                    $plant->setLighting('sunlight');

                    break;
                default:
                    $plant->setLighting('unknown');
            }

            $plant->setBreeder($entityManager->getRepository(Breeder::class)->findOneBy(['seedfinder_id' => $form->get('breeder')->getData()]));
            $plant->setStrain($entityManager->getRepository(Strain::class)->findOneBy(['seedfinder_id' => $form->get('strain')->getData()]));

            $plant->setGrowth(1);
            $plant->setThread($this->cannaConsultantService->getThreadForPlant($plant));

            $plant->setUser($user);
            $entityManager->persist($plant);
            $entityManager->flush();

            return $this->redirectToRoute('growMate');
        }

        return $this->render('grow_mate/index.html.twig', [
            'plants' => $plants,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/grow-mate/{id}', name: 'growMate-plants')]
    public function show(Plant $plant): Response
    {
        if (!$this->weedWizardKernel->isUserPremium()) {
            $this->addFlash('error', 'Du brauchst ein Premium-Abo um den GrowMate zu nutzen');

            return $this->redirectToRoute('weedwizard_premium');
        }

        return $this->render('grow_mate/show.html.twig', [
            'plant' => $plant,
        ]);
    }

    #[Route('/growmate/delete/{id}', name: 'delete_plant', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        if (!$this->weedWizardKernel->isUserPremium()) {
            $this->addFlash('error', 'Du brauchst ein Premium-Abo um den GrowMate zu nutzen');

            return $this->redirectToRoute('weedwizard_premium');
        }

        $plant = $em->getRepository(Plant::class)->find($id);

        if (!$plant) {
            throw $this->createNotFoundException('Die Pflanze existiert nicht');
        }

        $em->remove($plant);
        $em->flush();

        return $this->redirectToRoute('growMate');
    }

    #[Route('/api/completeTask', name: 'complete_plant_task', methods: ['POST'])]
    public function addTask(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $plant = $em->getRepository(Plant::class)->find($data['plant_id']);

        if (!$plant) {
            return new JsonResponse([
                'error' => 'Die Pflanze existiert nicht',
            ], Response::HTTP_NOT_FOUND);
        }

        $weeklyTasks = $plant->getWeeklyTasks();

        switch ($data['task']) {
            case 'water':
                // Check if the water array already exists in the weeklyTasks array
                if (!array_key_exists('water', $weeklyTasks)) {
                    $weeklyTasks['water'] = [];
                }

                // Check if the plant was already watered since the last 5 days
                $lastWatering = end($weeklyTasks['water']);
                $lastWateringDate = new \DateTime($lastWatering['date'] ?? 'now -5 days');

                if ($lastWatering && $lastWateringDate->diff(new \DateTime())->days < 5) {
                    return new JsonResponse([
                        'error' => 'Die Pflanze wurde bereits in den letzten 5 Tagen gegossen.',
                    ], Response::HTTP_NOT_FOUND);
                }

                // Append the task water to the created array
                $now = new \DateTime();
                $weeklyTasks['water'][] = [
                    'date' => $now->format('Y-m-d H:i:s'),
                    'task' => 'water',
                ];

                break;
            case 'fertilize':
                if (!array_key_exists('fertilize', $weeklyTasks)) {
                    $weeklyTasks['fertilize'] = [];
                }

                $lastFertilize = end($weeklyTasks['fertilize']);
                $lastFertilizeDate = new \DateTime($lastFertilize['date'] ?? 'now -15 days');

                if ($lastFertilizeDate->diff(new \DateTime())->days < 14) {
                    return new JsonResponse([
                        'error' => 'Die Pflanze wurde bereits in den letzten 14 Tagen gedüngt.',
                    ], Response::HTTP_NOT_FOUND);
                }

                $now = new \DateTime();
                $weeklyTasks['fertilize'][] = [
                    'date' => $now->format('Y-m-d H:i:s'),
                    'task' => 'fertilize',
                ];

                break;
            case 'temperature':
                if (!array_key_exists('temperature', $weeklyTasks)) {
                    $weeklyTasks['temperature'] = [];
                }

                $lastFertilize = end($weeklyTasks['temperature']);
                $lastFertilizeDate = new \DateTime($lastFertilize['date'] ?? 'now -3 days');

                if ($lastFertilizeDate->diff(new \DateTime())->days < 2) {
                    return new JsonResponse([
                        'error' => 'Die Temperatur der Pflanze wurde bereits in den letzten 2 Tagen überprüft.',
                    ], Response::HTTP_NOT_FOUND);
                }

                $now = new \DateTime();
                $weeklyTasks['temperature'][] = [
                    'date' => $now->format('Y-m-d H:i:s'),
                    'task' => 'temperature',
                ];

                break;
            case 'pesticide':
                if (!array_key_exists('pesticide', $weeklyTasks)) {
                    $weeklyTasks['pesticide'] = [];
                }

                $lastFertilize = end($weeklyTasks['pesticide']);
                $lastFertilizeDate = new \DateTime($lastFertilize['date'] ?? 'now -8 days');

                if ($lastFertilizeDate->diff(new \DateTime())->days < 7) {
                    return new JsonResponse([
                        'error' => 'Die Pflanze wurde bereits in den letzten 7 Tagen mit Pestiziden behandelt.',
                    ], Response::HTTP_NOT_FOUND);
                }

                $now = new \DateTime();
                $weeklyTasks['pesticide'][] = [
                    'date' => $now->format('Y-m-d H:i:s'),
                    'task' => 'pesticide',
                ];

                break;
            default:
                throw $this->createNotFoundException('Die Aufgabe existiert nicht');
        }

        $plant->setWeeklyTasks($weeklyTasks);
        $em->flush();

        return new JsonResponse('Die Aufgabe wurde erfolgreich erledigt', Response::HTTP_OK);
    }
}
