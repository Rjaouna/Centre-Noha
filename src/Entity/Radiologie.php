<?php

namespace App\Entity;

use App\Repository\RadiologieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadiologieRepository::class)]
class Radiologie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'radiologies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ficheClient $patient = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(length: 10)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $compteRendu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fichier = null;

    #[ORM\Column]
    private ?\DateTime $prescriptionAt = null;

    /**
     * @var Collection<int, RadioType>
     */
    #[ORM\ManyToMany(targetEntity: RadioType::class, inversedBy: 'radiologies')]
    private Collection $radioType;

    public function __construct()
    {
        $this->radioType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?ficheClient
    {
        return $this->patient;
    }

    public function setPatient(?ficheClient $patient): static
    {
        $this->patient = $patient;

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

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCompteRendu(): ?string
    {
        return $this->compteRendu;
    }

    public function setCompteRendu(?string $compteRendu): static
    {
        $this->compteRendu = $compteRendu;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getPrescriptionAt(): ?\DateTime
    {
        return $this->prescriptionAt;
    }

    public function setPrescriptionAt(\DateTime $prescriptionAt): static
    {
        $this->prescriptionAt = $prescriptionAt;

        return $this;
    }

    /**
     * @return Collection<int, RadioType>
     */
    public function getRadioType(): Collection
    {
        return $this->radioType;
    }

    public function addRadioType(RadioType $radioType): static
    {
        if (!$this->radioType->contains($radioType)) {
            $this->radioType->add($radioType);
        }

        return $this;
    }

    public function removeRadioType(RadioType $radioType): static
    {
        $this->radioType->removeElement($radioType);

        return $this;
    }
}
