<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['company:read']],
    denormalizationContext: ['groups' => ['company:write']]
 )]

 #[Patch(security: "is_granted('edit', object)")]
 #[Delete(security: "is_granted('delete', object)")]
 #[GetCollection]
 #[Post(securityPostDenormalize: "is_granted('create', object)")]
 
 class Company
 {
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column]
     #[Groups(['company:read'])]
     private ?int $id = null;
 
     #[ORM\Column(length: 255)]
     #[Groups(['company:read', 'company:write'])]
     private ?string $name = null;
 
     #[ORM\Column(length: 14)]
     #[Groups(['company:read', 'company:write'])]
     private ?string $siret = null;
 
     #[ORM\Column(length: 255)]
     #[Groups(['company:read', 'company:write'])]
     private ?string $address = null;
 
     #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'Company')]
     private Collection $projects;

     /**
      * @var Collection<int, UserCompanyAssignement>
      */
     #[ORM\OneToMany(targetEntity: UserCompanyAssignement::class, mappedBy: 'Company')]
     private Collection $userCompanyAssignements;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->userCompanyAssignements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setCompany($this);
        }
        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            if ($project->getCompany() === $this) {
                $project->setCompany(null);
            }
        }
        return $this;
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
            $userCompanyAssignement->setCompany($this);
        }

        return $this;
    }

    public function removeUserCompanyAssignement(UserCompanyAssignement $userCompanyAssignement): static
    {
        if ($this->userCompanyAssignements->removeElement($userCompanyAssignement)) {
            if ($userCompanyAssignement->getCompany() === $this) {
                $userCompanyAssignement->setCompany(null);
            }
        }

        return $this;
    }
}
