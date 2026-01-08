<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?User $praticien = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?FicheClient $patient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heureDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heureFin = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiresAt = null;

    /**
     * @var Collection<int, WaitingRoom>
     */
    #[ORM\OneToMany(targetEntity: WaitingRoom::class, mappedBy: 'rdv')]
    private Collection $waitingRooms;

    public function __construct()
    {
        $this->waitingRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPraticien(): ?User
    {
        return $this->praticien;
    }

    public function setPraticien(?User $praticien): static
    {
        $this->praticien = $praticien;

        return $this;
    }

    public function getPatient(): ?FicheClient
    {
        return $this->patient;
    }

    public function setPatient(?FicheClient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTime
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTime $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTime
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTime $heureFin): static
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return Collection<int, WaitingRoom>
     */
    public function getWaitingRooms(): Collection
    {
        return $this->waitingRooms;
    }

    public function addWaitingRoom(WaitingRoom $waitingRoom): static
    {
        if (!$this->waitingRooms->contains($waitingRoom)) {
            $this->waitingRooms->add($waitingRoom);
            $waitingRoom->setRdv($this);
        }

        return $this;
    }

    public function removeWaitingRoom(WaitingRoom $waitingRoom): static
    {
        if ($this->waitingRooms->removeElement($waitingRoom)) {
            // set the owning side to null (unless already changed)
            if ($waitingRoom->getRdv() === $this) {
                $waitingRoom->setRdv(null);
            }
        }

        return $this;
    }
}
