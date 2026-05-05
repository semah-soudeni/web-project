<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'memberships')]
class Memberships
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Etudiant::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Etudiant $user = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Clubs::class)]
    #[ORM\JoinColumn(name: 'club_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Clubs $club = null;

    #[ORM\Column(type: Types::STRING, columnDefinition: "ENUM('member', 'admin', 'vpa', 'vpt')", options: ['default' => 'member'])]
    private ?string $role = 'member';

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $joinedAt = null;

    public function getUser(): ?Etudiant
    {
        return $this->user;
    }

    public function setUser(?Etudiant $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getClub(): ?Clubs
    {
        return $this->club;
    }

    public function setClub(?Clubs $club): static
    {
        $this->club = $club;

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

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): static
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }
}
