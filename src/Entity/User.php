<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, Disponibilite>
     */
    #[ORM\OneToMany(targetEntity: Disponibilite::class, mappedBy: 'praticien')]
    private \Doctrine\Common\Collections\Collection $disponibilites;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, Indisponibilite>
     */
    #[ORM\OneToMany(targetEntity: Indisponibilite::class, mappedBy: 'praticien')]
    private \Doctrine\Common\Collections\Collection $indisponibilites;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'praticien')]
    private \Doctrine\Common\Collections\Collection $rendezVouses;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, WaitingRoom>
     */
    #[ORM\OneToMany(targetEntity: WaitingRoom::class, mappedBy: 'praticien')]
    private \Doctrine\Common\Collections\Collection $waitingRooms;

    public function __construct()
    {
        $this->disponibilites = new ArrayCollection();
        $this->indisponibilites = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
        $this->waitingRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Disponibilite>
     */
    public function getDisponibilites(): \Doctrine\Common\Collections\Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): static
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->add($disponibilite);
            $disponibilite->setPraticien($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): static
    {
        if ($this->disponibilites->removeElement($disponibilite)) {
            // set the owning side to null (unless already changed)
            if ($disponibilite->getPraticien() === $this) {
                $disponibilite->setPraticien(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Indisponibilite>
     */
    public function getIndisponibilites(): \Doctrine\Common\Collections\Collection
    {
        return $this->indisponibilites;
    }

    public function addIndisponibilite(Indisponibilite $indisponibilite): static
    {
        if (!$this->indisponibilites->contains($indisponibilite)) {
            $this->indisponibilites->add($indisponibilite);
            $indisponibilite->setPraticien($this);
        }

        return $this;
    }

    public function removeIndisponibilite(Indisponibilite $indisponibilite): static
    {
        if ($this->indisponibilites->removeElement($indisponibilite)) {
            // set the owning side to null (unless already changed)
            if ($indisponibilite->getPraticien() === $this) {
                $indisponibilite->setPraticien(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, RendezVous>
     */
    public function getRendezVouses(): \Doctrine\Common\Collections\Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setPraticien($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getPraticien() === $this) {
                $rendezVouse->setPraticien(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, WaitingRoom>
     */
    public function getWaitingRooms(): \Doctrine\Common\Collections\Collection
    {
        return $this->waitingRooms;
    }

    public function addWaitingRoom(WaitingRoom $waitingRoom): static
    {
        if (!$this->waitingRooms->contains($waitingRoom)) {
            $this->waitingRooms->add($waitingRoom);
            $waitingRoom->setPraticien($this);
        }

        return $this;
    }

    public function removeWaitingRoom(WaitingRoom $waitingRoom): static
    {
        if ($this->waitingRooms->removeElement($waitingRoom)) {
            // set the owning side to null (unless already changed)
            if ($waitingRoom->getPraticien() === $this) {
                $waitingRoom->setPraticien(null);
            }
        }

        return $this;
    }
}
