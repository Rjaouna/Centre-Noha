<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RendezVousRepository;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['rdv:read'])]
    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?FicheClient $client = null;

    #[Groups(['rdv:read'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateRdvAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['rdv:read'])]
    private ?string $motif = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['rdv:read'])]
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
