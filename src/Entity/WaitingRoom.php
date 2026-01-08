<?php

namespace App\Entity;

use App\Entity\FicheClient;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\RendezVous;
use App\Repository\WaitingRoomRepository;

#[ORM\Entity(repositoryClass: WaitingRoomRepository::class)]
class WaitingRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'waitingRooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheClient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'waitingRooms')]
    private ?User $praticien = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $queueDate = null;

    #[ORM\ManyToOne(inversedBy: 'waitingRooms')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RendezVous $rdv = null;

    #[ORM\Column]
    private ?bool $hasRdv = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $arriveAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $calledAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $consultationAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $doneAt = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $note = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPraticien(): ?User
    {
        return $this->praticien;
    }

    public function setPraticien(?User $praticien): static
    {
        $this->praticien = $praticien;

        return $this;
    }

    public function getQueueDate(): ?\DateTimeImmutable
    {
        return $this->queueDate;
    }

    public function setQueueDate(\DateTimeImmutable $queueDate): static
    {
        $this->queueDate = $queueDate;

        return $this;
    }

    public function getRdv(): ?RendezVous
    {
        return $this->rdv;
    }

    public function setRdv(?RendezVous $rdv): static
    {
        $this->rdv = $rdv;

        return $this;
    }

    public function hasRdv(): ?bool
    {
        return $this->hasRdv;
    }

    public function setHasRdv(bool $hasRdv): static
    {
        $this->hasRdv = $hasRdv;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getArriveAt(): ?\DateTimeImmutable
    {
        return $this->arriveAt;
    }

    public function setArriveAt(\DateTimeImmutable $arriveAt): static
    {
        $this->arriveAt = $arriveAt;

        return $this;
    }

    public function getCalledAt(): ?\DateTimeImmutable
    {
        return $this->calledAt;
    }

    public function setCalledAt(?\DateTimeImmutable $calledAt): static
    {
        $this->calledAt = $calledAt;

        return $this;
    }

    public function getConsultationAt(): ?\DateTimeImmutable
    {
        return $this->consultationAt;
    }

    public function setConsultationAt(?\DateTimeImmutable $consultationAt): static
    {
        $this->consultationAt = $consultationAt;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeImmutable
    {
        return $this->doneAt;
    }

    public function setDoneAt(?\DateTimeImmutable $doneAt): static
    {
        $this->doneAt = $doneAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }
}
