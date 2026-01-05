<?php

namespace App\Entity;

use App\Repository\RadioTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadioTypeRepository::class)]
class RadioType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $zone_corps = null;

    /**
     * @var Collection<int, Radiologie>
     */
    #[ORM\ManyToMany(targetEntity: Radiologie::class, mappedBy: 'radioType')]
    private Collection $radiologies;

    public function __construct()
    {
        $this->radiologies = new ArrayCollection();
    }

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

    public function getZoneCorps(): ?string
    {
        return $this->zone_corps;
    }

    public function setZoneCorps(string $zone_corps): static
    {
        $this->zone_corps = $zone_corps;

        return $this;
    }

    /**
     * @return Collection<int, Radiologie>
     */
    public function getRadiologies(): Collection
    {
        return $this->radiologies;
    }

    public function addRadiology(Radiologie $radiology): static
    {
        if (!$this->radiologies->contains($radiology)) {
            $this->radiologies->add($radiology);
            $radiology->addRadioType($this);
        }

        return $this;
    }

    public function removeRadiology(Radiologie $radiology): static
    {
        if ($this->radiologies->removeElement($radiology)) {
            $radiology->removeRadioType($this);
        }

        return $this;
    }
}
