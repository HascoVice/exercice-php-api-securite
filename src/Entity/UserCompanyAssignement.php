<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserCompanyAssignementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ORM\Entity(repositoryClass: UserCompanyAssignementRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['UserCompanyAssignement:read']],
    denormalizationContext: ['groups' => ['UserCompanyAssignement:write']]
)]
#[Patch(security: "is_granted('edit', object)")]
#[Delete(security: "is_granted('delete', object)")]
#[GetCollection]
#[Post(securityPostDenormalize: "is_granted('create', object)")]
class UserCompanyAssignement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['UserCompanyAssignement:read', 'UserCompanyAssignement:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userCompanyAssignements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['UserCompanyAssignement:read', 'UserCompanyAssignement:write'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userCompanyAssignements', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['UserCompanyAssignement:read', 'UserCompanyAssignement:write'])]
    private ?Company $company = null;

    #[ORM\Column(length: 255)]
    #[Groups(['UserCompanyAssignement:read', 'UserCompanyAssignement:write'])]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): ?company
    {
        return $this->company;
    }

    public function setCompany(?company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
