<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileEditFormType;
use App\Form\ProfileEditPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class UserProfileController extends AbstractController
{
    #[Route('/userProfile', name: 'app_user_profile')]
    #[IsGranted('manage', message: 'Vous devez être connecté pour gérer votre profil.')]
    public function index(): Response
    {
        return $this->render('userProfile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/userProfile/edit', name: 'profile_edit')]
    #[IsGranted('manage', message: 'Vous devez être connecté pour gérer votre profil.')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('userProfile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/userProfile/edit-password', name: 'profile_edit_password')]
    #[IsGranted('manage', message: 'Vous devez être connecté pour gérer votre profil.')]
    public function editProfilePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe changé avec succès.');

            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('userProfile/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}