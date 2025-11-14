<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[Vich\UploadableField(mapping: "fiche_images", fileNameProperty: "fileName")]
	private ?File $imageFile = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $fileName = null;

	#[ORM\ManyToOne(inversedBy: 'images')]
	private ?FicheClient $client = null;

	#[ORM\Column(nullable: true)]
	private ?\DateTimeImmutable $updatedAt = null;

	// -------------------------
	//   GETTERS / SETTERS
	// -------------------------

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getImageFile(): ?File
	{
		return $this->imageFile;
	}

	public function setImageFile(?File $imageFile): void
	{
		$this->imageFile = $imageFile;

		if ($imageFile !== null) {
			$this->updatedAt = new \DateTimeImmutable();
		}
	}

	public function getFileName(): ?string
	{
		return $this->fileName;
	}

	public function setFileName(?string $fileName): void
	{
		$this->fileName = $fileName;
	}

	public function getClient(): ?FicheClient
	{
		return $this->client;
	}

	public function setClient(?FicheClient $client): void
	{
		$this->client = $client;
	}

	public function getUpdatedAt(): ?\DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}
}
