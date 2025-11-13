<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $prixTotal = null;

    #[ORM\Column(length: 50)]
    private ?string $montantPaye = null;

    #[ORM\Column(length: 50)]
    private ?string $reste = null;

    #[ORM\Column(length: 50)]
    private ?string $typePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?FicheClient $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getMontantPaye(): ?string
    {
        return $this->montantPaye;
    }

    public function setMontantPaye(string $montantPaye): static
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    public function getReste(): ?string
    {
        return $this->reste;
    }

    public function setReste(string $reste): static
    {
        $this->reste = $reste;

        return $this;
    }

    public function getTypePaiement(): ?string
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(string $typePaiement): static
    {
        $this->typePaiement = $typePaiement;

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
