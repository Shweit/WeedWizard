<?php

// src/Controller/GrowMateController.php
namespace App\Controller;

use App\Entity\Plant;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrowMateController extends AbstractController
{
    #[Route('/grow-mate', name: 'growMate')]
    public function index(Request $request, EntityManagerInterface $entityManager, PlantRepository $plantRepository): Response
    {
        $plant = new Plant();
        $form = $this->createForm(PlantType::class, $plant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plant);
            $entityManager->flush();

            return $this->redirectToRoute('growMate');
        }

        $plants = $plantRepository->findAll();

        return $this->render('grow_mate/index.html.twig', [
            'form' => $form->createView(),
            'plants' => $plants,
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
