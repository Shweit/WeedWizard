<?php

// src/Controller/GrowMateController.php
namespace App\Controller;

use App\Entity\Breeder;
use App\Entity\Plant;
use App\Entity\Strain;
use App\Form\PlantType;
use App\Repository\PlantRepository;
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
    ) {}

    #[Route('/grow-mate', name: 'growMate')]
    public function index(Request $request, EntityManagerInterface $entityManager, PlantRepository $plantRepository): Response
    {
        $user = $this->getUser();
        $plants = $entityManager->getRepository(Plant::class)->findBy(['user' => $user]);

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
        return $this->render('grow_mate/show.html.twig', [
            'plant' => $plant,
        ]);
    }

    #[Route('/growmate/delete/{id}', name: 'delete_plant', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $plant = $em->getRepository(Plant::class)->find($id);

        if (!$plant) {
            throw $this->createNotFoundException('Die Pflanze existiert nicht');
        }

        $em->remove($plant);
        $em->flush();

        return $this->redirectToRoute('growMate');
    }
}
