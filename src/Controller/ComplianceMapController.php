<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ComplianceMapController extends AbstractController
{
    #[Route('/compliance-map', name: 'weedwizard_compliance_map')]
    public function index(): Response
    {
        return $this->render('compliance_map/index.html.twig', [
            'controller_name' => 'ComplianceMapController',
        ]);
    }
}
