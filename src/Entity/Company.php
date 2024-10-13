<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['company:read']],
    denormalizationContext: ['groups' => ['company:write']]
 )]
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
 
     #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'companies')]
    //  #[Groups(['company:read', 'company:write'])]
     private Collection $users;
 
     #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'company')]
     #[Groups(['company:read', 'company:write'])]
     private Collection $projects;

     /**
      * @var Collection<int, UserCompanyRoles>
      */
     #[ORM\OneToMany(targetEntity: UserCompanyRoles::class, mappedBy: 'company')]
     private Collection $userCompanyRoles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->userCompanyRoles = new ArrayCollection();
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

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);
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
     * @return Collection<int, UserCompanyRoles>
     */
    public function getUserCompanyRoles(): Collection
    {
        return $this->userCompanyRoles;
    }

    public function addUserCompanyRole(UserCompanyRoles $userCompanyRole): static
    {
        if (!$this->userCompanyRoles->contains($userCompanyRole)) {
            $this->userCompanyRoles->add($userCompanyRole);
            $userCompanyRole->setCompany($this);
        }

        return $this;
    }

    public function removeUserCompanyRole(UserCompanyRoles $userCompanyRole): static
    {
        if ($this->userCompanyRoles->removeElement($userCompanyRole)) {
            // set the owning side to null (unless already changed)
            if ($userCompanyRole->getCompany() === $this) {
                $userCompanyRole->setCompany(null);
            }
        }

        return $this;
    }
}
