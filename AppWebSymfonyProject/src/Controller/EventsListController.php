<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsListController extends AbstractController
{
    #[Route('/eventsList', name: 'app_events_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager->getRepository(Event::class)->findAll();

        return $this->render('eventsList/index.html.twig', [
            'events' => $events,
        ]);
    }
}