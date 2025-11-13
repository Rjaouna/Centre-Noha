<?php

namespace App\Entity;

use App\Repository\FicheClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: FicheClientRepository::class)]
class FicheClient
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $poids = null;

    #[ORM\Column(length: 19)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $dureeMaladie = null;

    #[ORM\Column(length: 50)]
    private ?string $typeMaladie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $traitement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'client')]
    private Collection $paiements;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // STRINGS
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getTypeMaladie(): ?string
    {
        return $this->typeMaladie;
    }

    public function setTypeMaladie(?string $typeMaladie): self
    {
        $this->typeMaladie = $typeMaladie;
        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(?string $traitement): self
    {
        $this->traitement = $traitement;
        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;
        return $this;
    }

    // INTEGERS
    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(?int $poids): self
    {
        $this->poids = $poids;
        return $this;
    }

    public function getDureeMaladie(): ?int
    {
        return $this->dureeMaladie;
    }

    public function setDureeMaladie(?int $dureeMaladie): self
    {
        $this->dureeMaladie = $dureeMaladie;
        return $this;
    }

    // RELATIONS
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setClient($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            if ($paiement->getClient() === $this) {
                $paiement->setClient(null);
            }
        }

        return $this;
    }
}
