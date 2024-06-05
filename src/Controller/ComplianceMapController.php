<?php

namespace App\Controller;

use App\Entity\MapMarkers;
use App\Form\AddMarkerFormType;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ComplianceMapController extends AbstractController
{
    #[Route('/compliance-map', name: 'weedwizard_compliance_map')]
    public function index(): Response
    {
        $addMarkerForm = $this->createForm(AddMarkerFormType::class);

        return $this->render('compliance_map/index.html.twig', [
            'controller_name' => 'ComplianceMapController',
            'addMarkerForm' => $addMarkerForm->createView(),
        ]);
    }
}
