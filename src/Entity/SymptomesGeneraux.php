<?php

namespace App\Entity;

use App\Repository\SymptomesGenerauxRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: SymptomesGenerauxRepository::class)]
class SymptomesGeneraux
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mauxTete = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mauxNuque = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $insomnie = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $hemorroides = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $enuresie = null;

    #[ORM\OneToOne(inversedBy: 'symptomesGeneraux', cascade: ['persist', 'remove'])]
    private ?FicheClient $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMauxTete(): ?string
    {
        return $this->mauxTete;
    }

    public function setMauxTete(?string $mauxTete): static
    {
        $this->mauxTete = $mauxTete;

        return $this;
    }

    public function getMauxNuque(): ?string
    {
        return $this->mauxNuque;
    }

    public function setMauxNuque(?string $mauxNuque): static
    {
        $this->mauxNuque = $mauxNuque;

        return $this;
    }

    public function getInsomnie(): ?string
    {
        return $this->insomnie;
    }

    public function setInsomnie(?string $insomnie): static
    {
        $this->insomnie = $insomnie;

        return $this;
    }

    public function getHemorroides(): ?string
    {
        return $this->hemorroides;
    }

    public function setHemorroides(?string $hemorroides): static
    {
        $this->hemorroides = $hemorroides;

        return $this;
    }

    public function getEnuresie(): ?string
    {
        return $this->enuresie;
    }

    public function setEnuresie(?string $enuresie): static
    {
        $this->enuresie = $enuresie;

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
