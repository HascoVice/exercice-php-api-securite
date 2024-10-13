<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security; // Utilise SecurityBundle ici
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CompanyVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security; // Utilise SecurityBundle\Security
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Company;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        dd($user);
        if (!$user instanceof User) {
            return false;
        }

        /** @var Company $company */
        $company = $subject;

        // Vérification des rôles globaux de l'utilisateur
        $userRoles = $user->getRoles();

        switch ($attribute) {
            case self::VIEW:
                // Tout le monde peut visualiser
                return true;
            case self::EDIT:
                return in_array('ROLE_ADMIN', $userRoles) || in_array('ROLE_MANAGER', $userRoles);
            case self::DELETE:
                // Seuls les admins ou managers peuvent modifier/supprimer
                return in_array('ROLE_ADMIN', $userRoles) || in_array('ROLE_MANAGER', $userRoles);
        }

        return false;
    }
}
