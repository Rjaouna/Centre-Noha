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

	#[ORM\Column(type: 'float')]
	private float $prixUnitaire = 0.0;

	#[ORM\Column]
	private \DateTimeImmutable $createdAt;

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

	public function getPrixUnitaire(): float
	{
		return $this->prixUnitaire;
	}

	public function setPrixUnitaire(float $prixUnitaire): static
	{
		$this->prixUnitaire = max(0, $prixUnitaire);
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
}
