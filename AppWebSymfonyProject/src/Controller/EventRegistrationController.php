<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventRegistration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EventRegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private MailerInterface $mailer;
    public function __construct(EntityManagerInterface $entityManager, Security $security, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->mailer = $mailer;
    }

    #[Route('/event/{id}/register', name: 'event_register')]
    public function register(Event $event): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            // Rediriger l'utilisateur non connecté vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour vous inscrire à un événement.');
            return $this->redirectToRoute('app_login'); // Adapter le nom de la route selon votre configuration
        }

        // Vérifier si l'utilisateur est déjà inscrit à cet événement
        $existingRegistration = $this->entityManager->getRepository(EventRegistration::class)
            ->findOneBy(['event' => $event, 'user' => $user]);

        if ($existingRegistration) {
            // Rediriger avec un message d'erreur si déjà inscrit
            $this->addFlash('error', 'Vous êtes déjà inscrit à cet événement.');
            return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
        }

        // Vérifier s'il reste des places disponibles
        if ($event->getMaxParticipants() !== null && $event->getEventRegistrations()->count() >= $event->getMaxParticipants()) {
            // Rediriger avec un message d'erreur si plus de places disponibles
            $this->addFlash('error', 'Désolé, cet événement est complet.');
            return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
        }

        // Créer une nouvelle inscription à l'événement
        $registration = new EventRegistration();
        $registration->setEvent($event);
        $registration->setUser($user);

        $this->entityManager->persist($registration);
        $this->entityManager->flush();

        // Envoyer un e-mail de confirmation (à implémenter plus tard)

        // Rediriger avec un message de succès
        $this->addFlash('success', 'Vous êtes inscrit à l\'événement avec succès.');
        return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
    }

    #[Route('/event/{id}/unregister', name: 'event_unregister')]
    public function unregister(Event $event): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            // Rediriger l'utilisateur non connecté vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour annuler votre inscription à un événement.');
            return $this->redirectToRoute('app_login'); // Adapter le nom de la route selon votre configuration
        }

        // Trouver l'inscription existante
        $registration = $this->entityManager->getRepository(EventRegistration::class)
            ->findOneBy(['event' => $event, 'user' => $user]);

        if (!$registration) {
            // Rediriger avec un message d'erreur si aucune inscription trouvée
            $this->addFlash('error', 'Vous n\'êtes pas inscrit à cet événement.');
            return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
        }

        // Supprimer l'inscription
        $this->entityManager->remove($registration);
        $this->entityManager->flush();

        // Envoyer un e-mail de confirmation (à implémenter plus tard)

        // Rediriger avec un message de succès
        $this->addFlash('success', 'Votre inscription à l\'événement a été annulée avec succès.');
        return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
    }
}