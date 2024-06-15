<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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



    #[Route('/event/{id}/edit', name: 'event_edit')]
    #[IsGranted('edit', subject: 'event', message: 'Vous n\'êtes pas autorisé à modifier cet événement.')]
    public function edit(Event $event, Request $request): Response
    {
        return $this->render('event/edit.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/event/{id}/delete', name: 'event_delete')]
    #[IsGranted('delete', subject: 'event', message: 'Vous n\'êtes pas autorisé à supprimer cet événement.')]
    public function delete(Event $event, Request $request): Response
    {
        return $this->render('event/delete.html.twig', [
            'event' => $event,
        ]);
    }
}