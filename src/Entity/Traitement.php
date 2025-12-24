<?php

namespace App\Entity;

use App\Repository\TraitementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraitementRepository::class)]
class Traitement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Symptome>
     */
    #[ORM\ManyToMany(targetEntity: Symptome::class, inversedBy: 'traitements')]
    private Collection $symptome;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medicine $medicine = null;

    /**
     * @var Collection<int, MaladieChronique>
     */
    #[ORM\ManyToMany(targetEntity: MaladieChronique::class, inversedBy: 'traitements')]
    private Collection $contreIndications;

    public function __construct()
    {
        $this->symptome = new ArrayCollection();
        $this->contreIndications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Symptome>
     */
    public function getSymptome(): Collection
    {
        return $this->symptome;
    }

    public function addSymptome(Symptome $symptome): static
    {
        if (!$this->symptome->contains($symptome)) {
            $this->symptome->add($symptome);
        }

        return $this;
    }

    public function removeSymptome(Symptome $symptome): static
    {
        $this->symptome->removeElement($symptome);

        return $this;
    }

    public function getMedicine(): ?Medicine
    {
        return $this->medicine;
    }

    public function setMedicine(?Medicine $medicine): static
    {
        $this->medicine = $medicine;

        return $this;
    }

    /**
     * @return Collection<int, MaladieChronique>
     */
    public function getContreIndications(): Collection
    {
        return $this->contreIndications;
    }

    public function addContreIndication(MaladieChronique $contreIndication): static
    {
        if (!$this->contreIndications->contains($contreIndication)) {
            $this->contreIndications->add($contreIndication);
        }

        return $this;
    }

    public function removeContreIndication(MaladieChronique $contreIndication): static
    {
        $this->contreIndications->removeElement($contreIndication);

        return $this;
    }
}
