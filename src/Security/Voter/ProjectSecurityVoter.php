<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\UserCompanyAssignement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security; 
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectSecurityVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const CREATE = 'create';

    private Security $security;
    private $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em; 
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::CREATE])
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if (!$user instanceof User) {
            return false;
        }

        /** @var Project $project */
        $project = $subject;

        $company = $project->getCompany();
        $userCompanyAssignement = $this->em->getRepository(UserCompanyAssignement::class)->findOneBy(["company" => $company, "user" => $user]);
        if (!$userCompanyAssignement) {
            return false;
        }

        $userRole = $userCompanyAssignement->getRole();

        return match ($attribute) {
            self::EDIT =>  $userRole === "ROLE_ADMIN" || $userRole === "ROLE_MANAGER",
            self::CREATE => $userRole === "ROLE_ADMIN" || $userRole === "ROLE_MANAGER",
            self::DELETE => $userRole === "ROLE_ADMIN" || $userRole === "ROLE_MANAGER"
        };
    }
}
