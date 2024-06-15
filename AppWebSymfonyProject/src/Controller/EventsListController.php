<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
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
    public function edit(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_events_list');
        }

        return $this->render('eventsList/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/{id}/delete', name: 'event_delete')]
    #[IsGranted('delete', subject: 'event', message: 'Vous n\'êtes pas autorisé à supprimer cet événement.')]
    public function delete(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager->remove($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_events_list');
        }

        return $this->render('eventsList/delete.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/events', name: 'app_event_list')]
    public function index(EventRepository $eventRepository, Request $request): Response
    {
        $search = $request->query->get('search', '');
        $sortPlaces = $request->query->get('sort_places', false);
        $sortDate = $request->query->get('sort_date', false);

        $queryBuilder = $eventRepository->createQueryBuilder('e');

        if ($search) {
            $queryBuilder->andWhere('e.title LIKE :search OR e.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($sortDate) {
            $queryBuilder->orderBy('e.dateTime', 'ASC');
        }

        $events = $queryBuilder->getQuery()->getResult();

        // Trier les événements par nombre de places restantes si demandé
        if ($sortPlaces) {
            usort($events, function($a, $b) {
                return $b->getRemainingPlaces() - $a->getRemainingPlaces();
            });
        }

        return $this->render('eventsList/index.html.twig', [
            'events' => $events,
        ]);
    }
}