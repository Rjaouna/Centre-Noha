<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\FicheClientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

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
    #[Groups(['suivi_read', 'admission_read', 'rdv:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Groups(['suivi_read', 'admission_read', 'rdv:read'])]
    private ?string $ville = null;

    #[Groups(['suivi_read', 'admission_read'])]
    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $age = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['suivi_read'])]
    private ?string $poids = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['suivi_read'])]
    private ?string $dureeMaladie = null;

    #[ORM\Column(length: 50)]
    #[Groups(['suivi_read', 'admission_read'])]
    private ?string $typeMaladie = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['suivi_read'])]
    private ?string $traitement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'client')]
    private Collection $paiements;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setClient($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getClient() === $this) {
                $image->setClient(null);
            }
        }

        return $this;
    }

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?TroublesDigestifs $troublesDigestifs = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?SymptomesGeneraux $symptomesGeneraux = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?MaladiesChroniques $maladiesChroniques = null;

    /**
     * @var Collection<int, SuiviSoin>
     */
    #[ORM\OneToMany(targetEntity: SuiviSoin::class, mappedBy: 'patient')]
    private Collection $suiviSoins;

    #[ORM\Column]
    private ?bool $isOpen = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isConsulted = null;

    /**
     * @var Collection<int, DossierMedical>
     */
    #[ORM\OneToMany(targetEntity: DossierMedical::class, mappedBy: 'patient')]
    private Collection $dossierMedicals;

    /**
     * @var Collection<int, RecommendationLetter>
     */
    #[ORM\OneToMany(targetEntity: RecommendationLetter::class, mappedBy: 'patient')]
    private Collection $recommendationLetters;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateNaissance = null;

    /**
     * @var Collection<int, ArretMaladie>
     */
    #[ORM\OneToMany(targetEntity: ArretMaladie::class, mappedBy: 'patient')]
    private Collection $arretMaladies;

    /**
     * @var Collection<int, AnalysePrescription>
     */
    #[ORM\OneToMany(targetEntity: AnalysePrescription::class, mappedBy: 'patient')]
    private Collection $analysePrescriptions;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'patient')]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
        $this->images = new ArrayCollection(); // 👍
        $this->suiviSoins = new ArrayCollection();
        $this->dossierMedicals = new ArrayCollection();
        $this->recommendationLetters = new ArrayCollection();
        $this->arretMaladies = new ArrayCollection();
        $this->analysePrescriptions = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
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

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(?\DateTimeInterface $age): static
    {
        $this->age = $age;
        return $this;
    }


    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(?string $poids): self
    {
        $this->poids = $poids;
        return $this;
    }

    public function getDureeMaladie(): ?string
    {
        return $this->dureeMaladie;
    }

    public function setDureeMaladie(?string $dureeMaladie): self
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

    public function getTroublesDigestifs(): ?TroublesDigestifs
    {
        return $this->troublesDigestifs;
    }

    public function setTroublesDigestifs(?TroublesDigestifs $troublesDigestifs): static
    {
        if ($troublesDigestifs && $troublesDigestifs->getClient() !== $this) {
            $troublesDigestifs->setClient($this);
        }

        $this->troublesDigestifs = $troublesDigestifs;

        return $this;
    }

    public function getSymptomesGeneraux(): ?SymptomesGeneraux
    {
        return $this->symptomesGeneraux;
    }

    public function setSymptomesGeneraux(?SymptomesGeneraux $symptomesGeneraux): static
    {
        // unset the owning side of the relation if necessary
        if ($symptomesGeneraux === null && $this->symptomesGeneraux !== null) {
            $this->symptomesGeneraux->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($symptomesGeneraux !== null && $symptomesGeneraux->getClient() !== $this) {
            $symptomesGeneraux->setClient($this);
        }

        $this->symptomesGeneraux = $symptomesGeneraux;

        return $this;
    }

    public function getMaladiesChroniques(): ?MaladiesChroniques
    {
        return $this->maladiesChroniques;
    }

    public function setMaladiesChroniques(?MaladiesChroniques $maladiesChroniques): static
    {
        // unset the owning side of the relation if necessary
        if ($maladiesChroniques === null && $this->maladiesChroniques !== null) {
            $this->maladiesChroniques->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($maladiesChroniques !== null && $maladiesChroniques->getClient() !== $this) {
            $maladiesChroniques->setClient($this);
        }

        $this->maladiesChroniques = $maladiesChroniques;

        return $this;
    }





    /**
     * @return Collection<int, SuiviSoin>
     */
    public function getSuiviSoins(): Collection
    {
        return $this->suiviSoins;
    }

    public function addSuiviSoin(SuiviSoin $suiviSoin): static
    {
        if (!$this->suiviSoins->contains($suiviSoin)) {
            $this->suiviSoins->add($suiviSoin);
            $suiviSoin->setPatient($this);
        }

        return $this;
    }

    public function removeSuiviSoin(SuiviSoin $suiviSoin): static
    {
        if ($this->suiviSoins->removeElement($suiviSoin)) {
            // set the owning side to null (unless already changed)
            if ($suiviSoin->getPatient() === $this) {
                $suiviSoin->setPatient(null);
            }
        }

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): static
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function isConsulted(): ?bool
    {
        return $this->isConsulted;
    }

    public function setIsConsulted(?bool $isConsulted): static
    {
        $this->isConsulted = $isConsulted;

        return $this;
    }

    /**
     * @return Collection<int, DossierMedical>
     */
    public function getDossierMedicals(): Collection
    {
        return $this->dossierMedicals;
    }

    public function addDossierMedical(DossierMedical $dossierMedical): static
    {
        if (!$this->dossierMedicals->contains($dossierMedical)) {
            $this->dossierMedicals->add($dossierMedical);
            $dossierMedical->setPatient($this);
        }

        return $this;
    }

    public function removeDossierMedical(DossierMedical $dossierMedical): static
    {
        if ($this->dossierMedicals->removeElement($dossierMedical)) {
            // set the owning side to null (unless already changed)
            if ($dossierMedical->getPatient() === $this) {
                $dossierMedical->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecommendationLetter>
     */
    public function getRecommendationLetters(): Collection
    {
        return $this->recommendationLetters;
    }

    public function addRecommendationLetter(RecommendationLetter $recommendationLetter): static
    {
        if (!$this->recommendationLetters->contains($recommendationLetter)) {
            $this->recommendationLetters->add($recommendationLetter);
            $recommendationLetter->setPatient($this);
        }

        return $this;
    }

    public function removeRecommendationLetter(RecommendationLetter $recommendationLetter): static
    {
        if ($this->recommendationLetters->removeElement($recommendationLetter)) {
            // set the owning side to null (unless already changed)
            if ($recommendationLetter->getPatient() === $this) {
                $recommendationLetter->setPatient(null);
            }
        }

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeImmutable $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAgePatient(): ?int
    {
        if (!$this->age) {
            return null;
        }

        $today = new \DateTimeImmutable();
        return $today->diff($this->age)->y;
    }

    /**
     * @return Collection<int, ArretMaladie>
     */
    public function getArretMaladies(): Collection
    {
        return $this->arretMaladies;
    }

    public function addArretMalady(ArretMaladie $arretMalady): static
    {
        if (!$this->arretMaladies->contains($arretMalady)) {
            $this->arretMaladies->add($arretMalady);
            $arretMalady->setPatient($this);
        }

        return $this;
    }

    public function removeArretMalady(ArretMaladie $arretMalady): static
    {
        if ($this->arretMaladies->removeElement($arretMalady)) {
            // set the owning side to null (unless already changed)
            if ($arretMalady->getPatient() === $this) {
                $arretMalady->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnalysePrescription>
     */
    public function getAnalysePrescriptions(): Collection
    {
        return $this->analysePrescriptions;
    }

    public function addAnalysePrescription(AnalysePrescription $analysePrescription): static
    {
        if (!$this->analysePrescriptions->contains($analysePrescription)) {
            $this->analysePrescriptions->add($analysePrescription);
            $analysePrescription->setPatient($this);
        }

        return $this;
    }

    public function removeAnalysePrescription(AnalysePrescription $analysePrescription): static
    {
        if ($this->analysePrescriptions->removeElement($analysePrescription)) {
            // set the owning side to null (unless already changed)
            if ($analysePrescription->getPatient() === $this) {
                $analysePrescription->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getPatient() === $this) {
                $rendezVouse->setPatient(null);
            }
        }

        return $this;
    }
}
