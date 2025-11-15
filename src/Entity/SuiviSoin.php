<?php

namespace App\Entity;

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
}
