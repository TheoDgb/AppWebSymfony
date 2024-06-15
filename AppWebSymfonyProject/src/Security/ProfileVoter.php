<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter
{
    const MANAGE = 'manage';

    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie que l'attribut est 'manage'
        return $attribute === self::MANAGE;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Autorisation de gérer le profil seulement si l'utilisateur est authentifié
        return $token->getUser() instanceof User;
    }
}
