<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationsController extends AbstractController
{
    #[Route('/registrations', name: 'app_registrations')]
    public function index(): Response
    {
        return $this->render('registrations/index.html.twig', [
            'controller_name' => 'RegistrationsController',
        ]);
    }
}