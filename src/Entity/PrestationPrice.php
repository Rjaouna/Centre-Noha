<?php

namespace App\Entity;

use App\Repository\PrestationPriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationPriceRepository::class)]
class PrestationPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $categorie = null;

    #[ORM\Column]
    private ?float $duree_minutes = null;

    // ✅ Prix en DECIMAL (précis pour l'argent)
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDureeMinutes(): ?float
    {
        return $this->duree_minutes;
    }

    public function setDureeMinutes(float $duree_minutes): static
    {
        $this->duree_minutes = $duree_minutes;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;
        return $this;
    }
}
