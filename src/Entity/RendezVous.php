<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?FicheClient $client = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateRdvAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?FicheClient
    {
        return $this->client;
    }

    public function setClient(?FicheClient $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getDateRdvAt(): ?\DateTimeImmutable
    {
        return $this->dateRdvAt;
    }

    public function setDateRdvAt(?\DateTimeImmutable $dateRdvAt): static
    {
        $this->dateRdvAt = $dateRdvAt;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
