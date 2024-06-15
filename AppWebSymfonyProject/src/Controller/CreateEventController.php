<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateEventController extends AbstractController
{
    #[Route('/createEvent', name: 'app_create_event')]
    #[IsGranted('manage', message: 'Vous devez être connecté pour gérer votre profil.')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $event->setUser($this->getUser()); // Attribuer l'utilisateur actuel comme créateur de l'événement
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('createEvent/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}