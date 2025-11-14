<?php

namespace App\Entity;

use App\Repository\TroublesDigestifsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TroublesDigestifsRepository::class)]
class TroublesDigestifs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $aciditeGastrique = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $constipation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $diarrhee = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $aspectSelles = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $gaz = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $eructation = null;

    #[ORM\OneToOne(inversedBy: 'troublesDigestifs', cascade: ['persist', 'remove'])]
    private ?FicheClient $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAciditeGastrique(): ?string
    {
        return $this->aciditeGastrique;
    }

    public function setAciditeGastrique(?string $aciditeGastrique): static
    {
        $this->aciditeGastrique = $aciditeGastrique;

        return $this;
    }

    public function getConstipation(): ?string
    {
        return $this->constipation;
    }

    public function setConstipation(?string $constipation): static
    {
        $this->constipation = $constipation;

        return $this;
    }

    public function getDiarrhee(): ?string
    {
        return $this->diarrhee;
    }

    public function setDiarrhee(?string $diarrhee): static
    {
        $this->diarrhee = $diarrhee;

        return $this;
    }

    public function getAspectSelles(): ?string
    {
        return $this->aspectSelles;
    }

    public function setAspectSelles(?string $aspectSelles): static
    {
        $this->aspectSelles = $aspectSelles;

        return $this;
    }

    public function getGaz(): ?string
    {
        return $this->gaz;
    }

    public function setGaz(?string $gaz): static
    {
        $this->gaz = $gaz;

        return $this;
    }

    public function getEructation(): ?string
    {
        return $this->eructation;
    }

    public function setEructation(?string $eructation): static
    {
        $this->eructation = $eructation;

        return $this;
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
}
