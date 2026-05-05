<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'events')]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200, unique: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $eventDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $eventTime = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $eventType = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $attendees = 0;

    #[ORM\Column(nullable: true)]
    private ?int $maxAttendees = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prizePool = null;

    #[ORM\ManyToMany(targetEntity: Clubs::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: 'club_events')]
    private Collection $clubs;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Register::class, orphanRemoval: true)]
    private Collection $registers;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Staff::class, orphanRemoval: true)]
    private Collection $staff;

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->registers = new ArrayCollection();
        $this->staff = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): static
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getEventTime(): ?\DateTimeInterface
    {
        return $this->eventTime;
    }

    public function setEventTime(?\DateTimeInterface $eventTime): static
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): static
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getAttendees(): ?int
    {
        return $this->attendees;
    }

    public function setAttendees(?int $attendees): static
    {
        $this->attendees = $attendees;

        return $this;
    }

    public function getMaxAttendees(): ?int
    {
        return $this->maxAttendees;
    }

    public function setMaxAttendees(?int $maxAttendees): static
    {
        $this->maxAttendees = $maxAttendees;

        return $this;
    }

    public function getPrizePool(): ?string
    {
        return $this->prizePool;
    }

    public function setPrizePool(?string $prizePool): static
    {
        $this->prizePool = $prizePool;

        return $this;
    }

    /**
     * @return Collection<int, Clubs>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Clubs $club): static
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
        }

        return $this;
    }

    public function removeClub(Clubs $club): static
    {
        $this->clubs->removeElement($club);

        return $this;
    }

    /**
     * @return Collection<int, Register>
     */
    public function getRegisters(): Collection
    {
        return $this->registers;
    }

    public function addRegister(Register $register): static
    {
        if (!$this->registers->contains($register)) {
            $this->registers->add($register);
            $register->setEvent($this);
        }

        return $this;
    }

    public function removeRegister(Register $register): static
    {
        if ($this->registers->removeElement($register)) {
            // set the owning side to null (unless already changed)
            if ($register->getEvent() === $this) {
                $register->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Staff>
     */
    public function getStaff(): Collection
    {
        return $this->staff;
    }

    public function addStaff(Staff $staff): static
    {
        if (!$this->staff->contains($staff)) {
            $this->staff->add($staff);
            $staff->setEvent($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): static
    {
        if ($this->staff->removeElement($staff)) {
            // set the owning side to null (unless already changed)
            if ($staff->getEvent() === $this) {
                $staff->setEvent(null);
            }
        }

        return $this;
    }
}
