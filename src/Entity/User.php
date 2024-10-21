<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
#[Patch(security: "is_granted('edit', object)")]
#[Delete(security: "is_granted('delete', object)")]
#[GetCollection]
#[Post(securityPostDenormalize: "is_granted('create', object)")]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'company:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:write'])]
    private ?string $password = null;

    /**
     * @var Collection<int, UserCompanyAssignement>
     */
    #[ORM\OneToMany(targetEntity: UserCompanyAssignement::class, mappedBy: 'user')]
    private Collection $userCompanyAssignements;

    public function __construct()
    {
        $this->userCompanyAssignements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    #[Groups(['user:read'])]
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    
    }


    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @return Collection<int, UserCompanyAssignement>
     */
    public function getUserCompanyAssignements(): Collection
    {
        return $this->userCompanyAssignements;
    }

    public function addUserCompanyAssignement(UserCompanyAssignement $userCompanyAssignement): static
    {
        if (!$this->userCompanyAssignements->contains($userCompanyAssignement)) {
            $this->userCompanyAssignements->add($userCompanyAssignement);
            $userCompanyAssignement->setUser($this);
        }

        return $this;
    }

    public function removeUserCompanyAssignement(UserCompanyAssignement $userCompanyAssignement): static
    {
        if ($this->userCompanyAssignements->removeElement($userCompanyAssignement)) {
       
            if ($userCompanyAssignement->getUser() === $this) {
                $userCompanyAssignement->setUser(null);
            }
        }

        return $this;
    }
}
