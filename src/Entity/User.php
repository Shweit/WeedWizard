<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Es gibt schon ein Account mit dieser E-Mail Adresse.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
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
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?CannabisVerein $joinedClub = null;

    /**
     * @var Collection<int, CannaDoseCalculator>
     */
    #[ORM\OneToMany(targetEntity: CannaDoseCalculator::class, mappedBy: 'user')]
    private Collection $cannaDoseCalculators;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type('\\DateTimeInterface')]
    private ?\DateTimeInterface $birthdate = null;

    /**
     * @var Collection<int, BudBash>
     */
    #[ORM\ManyToMany(targetEntity: BudBash::class, mappedBy: 'participants')]
    private Collection $attendedBudBashes;

    /**
     * @var Collection<int, BudBash>
     */
    #[ORM\OneToMany(targetEntity: BudBash::class, mappedBy: 'createdBy')]
    private Collection $hostedBudBashes;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'user')]
    private Collection $notifications;

    /**
     * @var Collection<int, Plant>
     */
    #[ORM\OneToMany(targetEntity: Plant::class, mappedBy: 'user')]
    private Collection $plants;

    /**
     * @var Collection<int, CannabisVerein>
     */
    #[ORM\ManyToMany(targetEntity: CannabisVerein::class, mappedBy: 'mitglieder')]
    private Collection $cannabisVereine;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CannabisVerein $createdClub = null;

    /**
     * @var Collection<int, MapMarkers>
     */
    #[ORM\OneToMany(targetEntity: MapMarkers::class, mappedBy: 'user')]
    private Collection $mapMarkers;

    public function __construct()
    {
        $this->attendedBudBashes = new ArrayCollection();
        $this->hostedBudBashes = new ArrayCollection();
        $this->cannabisVereine = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->plants = new ArrayCollection();
        $this->cannaDoseCalculators = new ArrayCollection();
        $this->mapMarkers = new ArrayCollection();
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
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
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
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, CannaDoseCalculator>
     */
    public function getCannaDoseCalculators(): Collection
    {
        return $this->cannaDoseCalculators;
    }

    public function addCannaDoseCalculator(CannaDoseCalculator $cannaDoseCalculator): static
    {
        if (!$this->cannaDoseCalculators->contains($cannaDoseCalculator)) {
            $this->cannaDoseCalculators->add($cannaDoseCalculator);
            $cannaDoseCalculator->setUser($this);
        }

        return $this;
    }

    public function removeCannaDoseCalculator(CannaDoseCalculator $cannaDoseCalculator): static
    {
        if ($this->cannaDoseCalculators->removeElement($cannaDoseCalculator)) {
            // set the owning side to null (unless already changed)
            if ($cannaDoseCalculator->getUser() === $this) {
                $cannaDoseCalculator->setUser(null);
            }
        }

        return $this;
    }

    public function getJoinedClub(): ?CannabisVerein
    {
        return $this->joinedClub;
    }

    public function setJoinedClub(?CannabisVerein $joinedClub): static
    {
        $this->joinedClub = $joinedClub;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return Collection<int, BudBash>
     */
    public function getAttendedBudBashes(): Collection
    {
        return $this->attendedBudBashes;
    }

    public function addAttendedBudBash(BudBash $attendedBudBash): static
    {
        if (!$this->attendedBudBashes->contains($attendedBudBash)) {
            $this->attendedBudBashes->add($attendedBudBash);
            $attendedBudBash->addParticipant($this);
        }

        return $this;
    }

    public function removeAttendedBudBash(BudBash $attendedBudBash): static
    {
        if ($this->attendedBudBashes->removeElement($attendedBudBash)) {
            $attendedBudBash->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, BudBash>
     */
    public function getHostedBudBashes(): Collection
    {
        return $this->hostedBudBashes;
    }

    public function addHostedBudBash(BudBash $hostedBudBash): static
    {
        if (!$this->hostedBudBashes->contains($hostedBudBash)) {
            $this->hostedBudBashes->add($hostedBudBash);
            $hostedBudBash->setCreatedBy($this);
        }

        return $this;
    }

    public function removeHostedBudBash(BudBash $hostedBudBash): static
    {
        if ($this->hostedBudBashes->removeElement($hostedBudBash)) {
            // set the owning side to null (unless already changed)
            if ($hostedBudBash->getCreatedBy() === $this) {
                $hostedBudBash->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plant>
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    public function addPlant(Plant $plant): static
    {
        if (!$this->plants->contains($plant)) {
            $this->plants->add($plant);
            $plant->setUser($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): static
    {
        if ($this->plants->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getUser() === $this) {
                $plant->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CannabisVerein>
     */
    public function getCannabisVereine(): Collection
    {
        return $this->cannabisVereine;
    }

    public function getCreatedClub(): ?CannabisVerein
    {
        return $this->createdClub;
    }

    public function setCreatedClub(?CannabisVerein $createdClub): static
    {
        $this->createdClub = $createdClub;

        return $this;
    }

    /**
     * @return Collection<int, MapMarkers>
     */
    public function getMapMarkers(): Collection
    {
        return $this->mapMarkers;
    }

    public function addMapMarker(MapMarkers $mapMarker): static
    {
        if (!$this->mapMarkers->contains($mapMarker)) {
            $this->mapMarkers->add($mapMarker);
            $mapMarker->setUser($this);
        }

        return $this;
    }

    public function removeMapMarker(MapMarkers $mapMarker): static
    {
        if ($this->mapMarkers->removeElement($mapMarker)) {
            // set the owning side to null (unless already changed)
            if ($mapMarker->getUser() === $this) {
                $mapMarker->setUser(null);
            }
        }

        return $this;
    }
}
