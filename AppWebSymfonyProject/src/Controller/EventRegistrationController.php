<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventRegistration;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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

    /**
     * @throws TransportExceptionInterface
     */
    /*
    // test d'envoi de mail /!\ autre que l'expéditeur lui-même car MailJet ne permet pas d'envoyer des emails à soi-même
    #[Route('/send-test-email', name: 'send_test_email')]
    public function sendTestEmail(): Response
    {
        $toEmail = 'TESTMAIL@TEST.com';

        $this->sendEmail(
            $this->mailer,
            $toEmail,
            'Test d\'envoi d\'email avec Symfony',
            'Test d\'envoi d\'email avec MailJet',
        );

        return new Response('Email de test envoyé à ' . $toEmail);
    }*/







    /**
     * @throws TransportExceptionInterface
     */
    private function sendEmail(MailerInterface $mailer, string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from('thdalgobbo@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($body);

        $mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/event/{id}/register', name: 'event_register')]
    public function register(Event $event): Response
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
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
            $this->addFlash('error', 'Désolé, cet événement est complet.');
            return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
        }

        // Créer une nouvelle inscription à l'événement
        $registration = new EventRegistration();
        $registration->setEvent($event);
        $registration->setUser($user);

        $this->entityManager->persist($registration);
        $this->entityManager->flush();

        $this->sendEmail(
            $this->mailer,
            $user->getEmail(),
            'Confirmation d\'inscription à l\'événement',
            'Vous êtes inscrit à l\'événement ' . $event->getTitle() . ' avec succès.'
        );

        // Rediriger avec un message de succès
        $this->addFlash('success', 'Vous êtes inscrit à l\'événement avec succès.');
        return $this->redirectToRoute('app_events_list');
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/event/{id}/unregister', name: 'event_unregister')]
    public function unregister(Event $event): Response
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            // Rediriger l'utilisateur non connecté vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour annuler votre inscription à un événement.');
            return $this->redirectToRoute('app_login');
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

        $this->sendEmail(
            $this->mailer,
            $user->getEmail(),
            'Annulation de l\'inscription à l\'événement',
            'Votre inscription à l\'événement ' . $event->getTitle() . ' a été annulée avec succès.'
        );

        // Rediriger avec un message de succès
        $this->addFlash('success', 'Votre inscription à l\'événement a été annulée avec succès.');
        return $this->redirectToRoute('app_events_list'); // Adapter le nom de la route selon votre configuration
    }
}