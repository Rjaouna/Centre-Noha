<?php

namespace App\Entity;

use App\Repository\MaladiesChroniquesRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: MaladiesChroniquesRepository::class)]
class MaladiesChroniques
{
	use TimestampableTrait;

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $diabetique = null;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $hypertendu = null;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $cholesterol = null;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $allergieNasale = null;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $autreMaladie = null;

	#[ORM\OneToOne(inversedBy: 'maladiesChroniques', cascade: ['persist', 'remove'])]
	private ?FicheClient $client = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getDiabetique(): ?string
	{
		return $this->diabetique;
	}

	public function setDiabetique(?string $diabetique): static
	{
		$this->diabetique = $diabetique;

		return $this;
	}

	public function getHypertendu(): ?string
	{
		return $this->hypertendu;
	}

	public function setHypertendu(?string $hypertendu): static
	{
		$this->hypertendu = $hypertendu;

		return $this;
	}

	public function getCholesterol(): ?string
	{
		return $this->cholesterol;
	}

	public function setCholesterol(?string $cholesterol): static
	{
		$this->cholesterol = $cholesterol;

		return $this;
	}

	public function getAllergieNasale(): ?string
	{
		return $this->allergieNasale;
	}

	public function setAllergieNasale(?string $allergieNasale): static
	{
		$this->allergieNasale = $allergieNasale;

		return $this;
	}

	public function getAutreMaladie(): ?string
	{
		return $this->autreMaladie;
	}

	public function setAutreMaladie(?string $autreMaladie): static
	{
		$this->autreMaladie = $autreMaladie;

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
