<?php

namespace App\Security;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie que l'attribut est l'un de ceux définis (view, edit, delete)
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        // Vérifie que le sujet est une instance de la classe Event
        if (!$subject instanceof Event) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Event $event */
        $event = $subject;
        $user = $token->getUser();

        if (!$user instanceof User) {
            // L'utilisateur n'est pas connecté, donc ne peut pas avoir accès
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                // Autorisation de voir l'événement pour tous les utilisateurs authentifiés
                return true;

            case self::EDIT:
            case self::DELETE:
                // Autorisation de modifier ou supprimer l'événement seulement si l'utilisateur est l'auteur de l'événement
                return $event->getUser() === $user;
        }

        return false;
    }
}