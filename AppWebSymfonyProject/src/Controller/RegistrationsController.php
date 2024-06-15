<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use Doctrine\ORM\EntityManagerInterface; // Importer EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registrations', name: 'app_registrations')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos inscriptions.');
        }

        // Récupérer les inscriptions de l'utilisateur
        $registrations = $this->entityManager
            ->getRepository(EventRegistration::class)
            ->findBy(['user' => $user]);

        return $this->render('registrations/index.html.twig', [
            'registrations' => $registrations,
        ]);
    }
}