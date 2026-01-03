<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
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

    // Total à payer
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $prixTotal = '0.00';

    // Montant déjà payé
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $montantPaye = '0.00';

    // Reste à payer
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $reste = '0.00';

    // ex: espece, carte, virement...
    #[ORM\Column(length: 30)]
    private ?string $typePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheClient $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixTotal(): string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): static
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }

    public function getMontantPaye(): string
    {
        return $this->montantPaye;
    }

    public function setMontantPaye(string $montantPaye): static
    {
        $this->montantPaye = $montantPaye;
        return $this;
    }

    public function getReste(): string
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

    public function setTypePaiement(?string $typePaiement): static
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

    // Statut calculé (pas en DB)
    public function getStatutCalc(): string
    {
        $reste = (float) $this->reste;
        $paye  = (float) $this->montantPaye;

        if ($reste <= 0.0) return 'solde';
        if ($paye > 0.0) return 'partiel';
        return 'impaye';
    }
}
