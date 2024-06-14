<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsListController extends AbstractController
{
    #[Route('/eventsList', name: 'app_events_list')]
    public function index(): Response
    {
        return $this->render('eventsList/index.html.twig', [
            'controller_name' => 'EventsListController',
        ]);
    }
}