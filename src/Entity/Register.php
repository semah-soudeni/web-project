<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'register')]
class Register
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Etudiant::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Etudiant $user = null;

    #[ORM\ManyToOne(targetEntity: Events::class, inversedBy: 'registers')]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Events $event = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $paid = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $teamName = null;

    #[ORM\Column(nullable: true)]
    private ?int $teamNbMemebers = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $links = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Etudiant
    {
        return $this->user;
    }

    public function setUser(?Etudiant $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): static
    {
        $this->paid = $paid;

        return $this;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(?string $teamName): static
    {
        $this->teamName = $teamName;

        return $this;
    }

    public function getTeamNbMemebers(): ?int
    {
        return $this->teamNbMemebers;
    }

    public function setTeamNbMemebers(?int $teamNbMemebers): static
    {
        $this->teamNbMemebers = $teamNbMemebers;

        return $this;
    }

    public function getLinks(): ?string
    {
        return $this->links;
    }

    public function setLinks(?string $links): static
    {
        $this->links = $links;

        return $this;
    }
}
