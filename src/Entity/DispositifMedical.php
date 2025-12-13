<?php

namespace App\Entity;

use App\Repository\DispositifMedicalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DispositifMedicalRepository::class)]
class DispositifMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $codeCnops = null;

    #[ORM\Column(length: 20)]
    private ?string $codeAnam = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?float $tarifReference = null;

    #[ORM\Column(length: 50)]
    private ?string $accordPrealable = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $piecesAFournir = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $piecesComplementaires = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCnops(): ?string
    {
        return $this->codeCnops;
    }

    public function setCodeCnops(string $codeCnops): static
    {
        $this->codeCnops = $codeCnops;

        return $this;
    }

    public function getCodeAnam(): ?string
    {
        return $this->codeAnam;
    }

    public function setCodeAnam(string $codeAnam): static
    {
        $this->codeAnam = $codeAnam;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTarifReference(): ?float
    {
        return $this->tarifReference;
    }

    public function setTarifReference(?float $tarifReference): static
    {
        $this->tarifReference = $tarifReference;

        return $this;
    }

    public function getAccordPrealable(): ?string
    {
        return $this->accordPrealable;
    }

    public function setAccordPrealable(string $accordPrealable): static
    {
        $this->accordPrealable = $accordPrealable;

        return $this;
    }

    public function getPiecesAFournir(): ?string
    {
        return $this->piecesAFournir;
    }

    public function setPiecesAFournir(?string $piecesAFournir): static
    {
        $this->piecesAFournir = $piecesAFournir;

        return $this;
    }

    public function getPiecesComplementaires(): ?string
    {
        return $this->piecesComplementaires;
    }

    public function setPiecesComplementaires(?string $piecesComplementaires): static
    {
        $this->piecesComplementaires = $piecesComplementaires;

        return $this;
    }
}
