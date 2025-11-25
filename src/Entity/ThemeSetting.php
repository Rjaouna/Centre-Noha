<?php

namespace App\Entity;

use App\Repository\ThemeSettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeSettingRepository::class)]
class ThemeSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 7)]
    private ?string $primaryColor = null;

    #[ORM\Column(length: 7)]
    private ?string $secondaryColor = null;

    #[ORM\Column(length: 7)]
    private ?string $successColor = null;

    #[ORM\Column(length: 7)]
    private ?string $infoColor = null;

    #[ORM\Column(length: 7)]
    private ?string $dangerColor = null;

    #[ORM\Column(length: 7)]
    private ?string $warningColor = null;

    #[ORM\Column(length: 7)]
    private ?string $lightColor = null;

    #[ORM\Column(length: 7)]
    private ?string $darkColor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor(string $primaryColor): static
    {
        $this->primaryColor = $primaryColor;

        return $this;
    }

    public function getSecondaryColor(): ?string
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor(string $secondaryColor): static
    {
        $this->secondaryColor = $secondaryColor;

        return $this;
    }

    public function getSuccessColor(): ?string
    {
        return $this->successColor;
    }

    public function setSuccessColor(string $successColor): static
    {
        $this->successColor = $successColor;

        return $this;
    }

    public function getInfoColor(): ?string
    {
        return $this->infoColor;
    }

    public function setInfoColor(string $infoColor): static
    {
        $this->infoColor = $infoColor;

        return $this;
    }

    public function getDangerColor(): ?string
    {
        return $this->dangerColor;
    }

    public function setDangerColor(string $dangerColor): static
    {
        $this->dangerColor = $dangerColor;

        return $this;
    }

    public function getWarningColor(): ?string
    {
        return $this->warningColor;
    }

    public function setWarningColor(string $warningColor): static
    {
        $this->warningColor = $warningColor;

        return $this;
    }

    public function getLightColor(): ?string
    {
        return $this->lightColor;
    }

    public function setLightColor(string $lightColor): static
    {
        $this->lightColor = $lightColor;

        return $this;
    }

    public function getDarkColor(): ?string
    {
        return $this->darkColor;
    }

    public function setDarkColor(string $darkColor): static
    {
        $this->darkColor = $darkColor;

        return $this;
    }
}
