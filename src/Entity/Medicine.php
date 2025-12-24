<?php

namespace App\Entity;

use App\Repository\MedicineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicineRepository::class)]
class Medicine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 13)]
    private ?string $code = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $dci = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $dosage = null;

    #[ORM\Column(length: 10)]
    private ?string $uniteDosage = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $forme = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $presentation = null;

    #[ORM\Column]
    private ?float $ppv = null;

    #[ORM\Column(nullable: true)]
    private ?float $ph = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isGeneric = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $tauxRembourssement = null;

    /**
     * @var Collection<int, SuiviSoin>
     */
    #[ORM\ManyToMany(targetEntity: SuiviSoin::class, mappedBy: 'medicine')]
    private Collection $suiviSoins;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\OneToMany(targetEntity: Traitement::class, mappedBy: 'medicine')]
    private Collection $traitements;

    public function __construct()
    {
        $this->suiviSoins = new ArrayCollection();
        $this->traitements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDci(): ?string
    {
        return $this->dci;
    }

    public function setDci(?string $dci): static
    {
        $this->dci = $dci;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(?string $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getUniteDosage(): ?string
    {
        return $this->uniteDosage;
    }

    public function setUniteDosage(string $uniteDosage): static
    {
        $this->uniteDosage = $uniteDosage;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(?string $forme): static
    {
        $this->forme = $forme;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): static
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getPpv(): ?float
    {
        return $this->ppv;
    }

    public function setPpv(float $ppv): static
    {
        $this->ppv = $ppv;

        return $this;
    }

    public function getPh(): ?float
    {
        return $this->ph;
    }

    public function setPh(?float $ph): static
    {
        $this->ph = $ph;

        return $this;
    }

    public function isGeneric(): ?bool
    {
        return $this->isGeneric;
    }

    public function setIsGeneric(?bool $isGeneric): static
    {
        $this->isGeneric = $isGeneric;

        return $this;
    }

    public function getTauxRembourssement(): ?int
    {
        return $this->tauxRembourssement;
    }

    public function setTauxRembourssement(?int $tauxRembourssement): static
    {
        $this->tauxRembourssement = $tauxRembourssement;

        return $this;
    }

    /**
     * @return Collection<int, SuiviSoin>
     */
    public function getSuiviSoins(): Collection
    {
        return $this->suiviSoins;
    }

    public function addSuiviSoin(SuiviSoin $suiviSoin): static
    {
        if (!$this->suiviSoins->contains($suiviSoin)) {
            $this->suiviSoins->add($suiviSoin);
            $suiviSoin->addMedicine($this);
        }

        return $this;
    }

    public function removeSuiviSoin(SuiviSoin $suiviSoin): static
    {
        if ($this->suiviSoins->removeElement($suiviSoin)) {
            $suiviSoin->removeMedicine($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Traitement>
     */
    public function getTraitements(): Collection
    {
        return $this->traitements;
    }

    public function addTraitement(Traitement $traitement): static
    {
        if (!$this->traitements->contains($traitement)) {
            $this->traitements->add($traitement);
            $traitement->setMedicine($this);
        }

        return $this;
    }

    public function removeTraitement(Traitement $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            // set the owning side to null (unless already changed)
            if ($traitement->getMedicine() === $this) {
                $traitement->setMedicine(null);
            }
        }

        return $this;
    }
}
