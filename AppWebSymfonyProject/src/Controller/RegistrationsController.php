<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Event;
use App\Entity\EventRegistration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationsController extends AbstractController
{
    #[Route('/registrations', name: 'app_registrations')]
    #[IsGranted('edit', subject: 'event', message: 'Vous n\'êtes pas autorisé à vous inscrire à un événement.')]
    public function index(): Response
    {
        return $this->render('registrations/index.html.twig', [
            'controller_name' => 'RegistrationsController',
        ]);
    }
}