<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
class DossierMedical
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dossierMedicals')]
    private ?FicheClient $patient = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, SuiviSoin>
     */
    #[ORM\OneToMany(targetEntity: SuiviSoin::class, mappedBy: 'dossierMedical')]
    private Collection $suiviSoins;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $autre = null;

    public function __construct()
    {
        $this->suiviSoins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?FicheClient
    {
        return $this->patient;
    }

    public function setPatient(?FicheClient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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
            $suiviSoin->setDossierMedical($this);
        }

        return $this;
    }

    public function removeSuiviSoin(SuiviSoin $suiviSoin): static
    {
        if ($this->suiviSoins->removeElement($suiviSoin)) {
            // set the owning side to null (unless already changed)
            if ($suiviSoin->getDossierMedical() === $this) {
                $suiviSoin->setDossierMedical(null);
            }
        }

        return $this;
    }

    public function getAutre(): ?string
    {
        return $this->autre;
    }

    public function setAutre(?string $autre): static
    {
        $this->autre = $autre;

        return $this;
    }
}
