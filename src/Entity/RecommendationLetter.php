<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\RecommendationLetterRepository;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecommendationLetterRepository::class)]
class RecommendationLetter
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recommendationLetters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheClient $patient = null;

    #[ORM\Column]
    private ?int $hopitalId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

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

    public function getHopitalId(): ?int
    {
        return $this->hopitalId;
    }

    public function setHopitalId(int $hopitalId): static
    {
        $this->hopitalId = $hopitalId;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
