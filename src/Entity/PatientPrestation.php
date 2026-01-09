<?php

namespace App\Entity;

use App\Repository\PatientPrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientPrestationRepository::class)]
class PatientPrestation
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'patientPrestations')]
	#[ORM\JoinColumn(nullable: false)]
	private ?FicheClient $patient = null;

	#[ORM\ManyToOne]
	#[ORM\JoinColumn(nullable: false)]
	private ?PrestationPrice $prestation = null;

	#[ORM\Column]
	private int $quantite = 1;

	// ✅ Argent = DECIMAL -> stocké en string (Doctrine)
	#[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
	private ?string $prixUnitaire = '0.00';

	#[ORM\Column]
	private \DateTimeImmutable $createdAt;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $nom = null;

	#[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
	private ?string $totalPrestation = null;

	public function __construct()
	{
		$this->createdAt = new \DateTimeImmutable();
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

	public function getPrestation(): ?PrestationPrice
	{
		return $this->prestation;
	}

	public function setPrestation(?PrestationPrice $prestation): static
	{
		$this->prestation = $prestation;
		return $this;
	}

	public function getQuantite(): int
	{
		return $this->quantite;
	}

	public function setQuantite(int $quantite): static
	{
		$this->quantite = max(1, $quantite);
		return $this;
	}

	public function getPrixUnitaire(): ?string
	{
		return $this->prixUnitaire;
	}

	public function setPrixUnitaire(?string $prixUnitaire): static
	{
		$this->prixUnitaire = $prixUnitaire;
		return $this;
	}

	public function getCreatedAt(): \DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTimeImmutable $createdAt): static
	{
		$this->createdAt = $createdAt;
		return $this;
	}

	/**
	 * ✅ Total calculé (non stocké en base)
	 */
	public function getTotal(): float
	{
		return $this->prixUnitaire * $this->quantite;
	}

	public function getNom(): ?string
	{
		return $this->nom;
	}

	public function setNom(?string $nom): static
	{
		$this->nom = $nom;

		return $this;
	}

	public function getTotalPrestation(): ?string
	{
		return $this->totalPrestation;
	}

	public function setTotalPrestation(?string $totalPrestation): static
	{
		$this->totalPrestation = $totalPrestation;
		return $this;
	}
}
