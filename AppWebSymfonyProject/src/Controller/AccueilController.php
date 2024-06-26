<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function accueil(): Response
    {
        return $this->redirectToRoute('app_accueil'); // Redirection vers la route 'app_accueil'
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'events' => $events,
        ]);
    }
}