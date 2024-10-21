<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\UserCompanyAssignement;
use App\Entity\UserCompanyRole;
use Symfony\Bundle\SecurityBundle\Security; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserSecurityVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const CREATE = 'create';

    private Security $security;
    private $passwordHasher;

    public function __construct(Security $security , UserPasswordHasherInterface $passwordHasher)
    {
        $this->security = $security; 
        $this->passwordHasher = $passwordHasher;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::CREATE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
         /** @var User $User */
         $userAction = $subject;

        $connectedUser = $token->getUser();

        if ($attribute === self::CREATE) {
            if ($userAction->getPassword()) {
                $hashedPassword = $this->passwordHasher->hashPassword($userAction, $userAction->getPassword());
                $userAction->setPassword($hashedPassword);
            }
            return true;
        }

        if (!$connectedUser instanceof User) {
            return false;
        }


        if ($connectedUser !== $userAction) {
            return false;
        }

        return true;
    }
}
