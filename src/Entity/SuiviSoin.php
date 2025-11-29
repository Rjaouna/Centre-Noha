<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SuiviSoinRepository;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: SuiviSoinRepository::class)]
class SuiviSoin
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'suiviSoins')]
    #[Groups(['suivi_read'])]
    private ?FicheClient $patient = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['suivi_read'])]
    private ?string $diagnostic = null;

    #[ORM\ManyToOne(inversedBy: 'suiviSoins')]
    private ?DossierMedical $dossierMedical = null;

    /**
     * @var Collection<int, Medicine>
     */
    #[ORM\ManyToMany(targetEntity: Medicine::class, inversedBy: 'suiviSoins')]
    private Collection $medicine;

    public function __construct()
    {
        $this->medicine = new ArrayCollection();
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

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?string $diagnostic): static
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): static
    {
        $this->dossierMedical = $dossierMedical;

        return $this;
    }

    /**
     * @return Collection<int, Medicine>
     */
    public function getMedicine(): Collection
    {
        return $this->medicine;
    }

    public function addMedicine(Medicine $medicine): static
    {
        if (!$this->medicine->contains($medicine)) {
            $this->medicine->add($medicine);
        }

        return $this;
    }

    public function removeMedicine(Medicine $medicine): static
    {
        $this->medicine->removeElement($medicine);

        return $this;
    }
}
