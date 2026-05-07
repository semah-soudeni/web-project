<?php

namespace App\Entity;

use App\Enum\Role;
use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255,unique:true)]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(enumType: Role::class)]
    private ?Role $role = null;

    # not mandatroy,we can remove it if we want
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Register::class)]
    private Collection $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
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

    public function getRole(): ?Role
    {
        return $this->role;
    }



    public function setRole(Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }


    // these methodes must be implemented in order to use symfony security system(app.user twli treferi to etudiant)
    public function getUserIdentifier(): string
    {
        return (string) $this->email; // Tell Symfony what field identifies the user
    }

    public function getRoles(): array
    {
        $roles = [$this->role->value];
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function eraseCredentials(): void {}
}
