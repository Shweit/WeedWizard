<?php

// src/Controller/GrowMateController.php

namespace App\Controller;

use App\Entity\Breeder;
use App\Entity\Plant;
use App\Entity\Strain;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use App\Services\CannaConsultantServiceV2;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrowMateController extends AbstractController
{
    public function __construct(
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly CannaConsultantServiceV2 $cannaConsultantService,
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
}
