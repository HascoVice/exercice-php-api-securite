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

class UserCompanyAssignementSecurityVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const CREATE = 'create';

    private Security $security;
    private $em;

    public function __construct(Security $security , EntityManagerInterface $em)
    {
        $this->security = $security; 
        $this->em = $em;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE , self::CREATE])
            && $subject instanceof UserCompanyAssignement;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();


        if (!$user instanceof User) {
            return false;
        }

        /** @var UserCompanyAssignement $userCompanyAssignement */
        $userCompanyAssignement = $subject; 
        $company = $userCompanyAssignement->getCompany();
        $connectedUserCompanyAssignement = $this->em->getRepository(UserCompanyAssignement::class)->findOneBy(["company" => $company, "user" => $user]);

        if ($attribute === self::CREATE) {
            $existingAssignement = $this->em->getRepository(UserCompanyAssignement::class)
            ->findOneBy(["company" => $company, "user" => $userCompanyAssignement->getUser()]);
            if ($existingAssignement) {
                return false;
            }
        }
      

        if(!$connectedUserCompanyAssignement){
            return false;
        }

        $userRole = $connectedUserCompanyAssignement->getRole();

        return match($attribute) {
            self::EDIT =>  $userRole === "ROLE_ADMIN",
            self::CREATE => $userRole === "ROLE_ADMIN",
            self::DELETE => $userRole === "ROLE_ADMIN"
        };
    }
}

