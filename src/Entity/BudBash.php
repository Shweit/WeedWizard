<?php

namespace App\Entity;

use App\Repository\BudBashRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudBashRepository::class)]
class BudBash
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?float $entrance_fee = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'attendedBudBashes')]
    private Collection $participants;

    #[ORM\ManyToOne(inversedBy: 'hostedBudBashes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    /**
     * @var ?Collection<int, BudBashCheckAttendance>
     */
    #[ORM\OneToMany(targetEntity: BudBashCheckAttendance::class, mappedBy: 'BudBashParty', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Collection $budBashCheckAttendances = null;

    #[ORM\Column(length: 255)]
    private ?string $coordinates = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extraInfo = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->budBashCheckAttendances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getEntranceFee(): ?float
    {
        return $this->entrance_fee;
    }

    public function setEntranceFee(float $entrance_fee): static
    {
        $this->entrance_fee = $entrance_fee;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, BudBashCheckAttendance>
     */
    public function getBudBashCheckAttendances(): Collection
    {
        return $this->budBashCheckAttendances;
    }

    public function addBudBashCheckAttendance(BudBashCheckAttendance $budBashCheckAttendance): static
    {
        if (!$this->budBashCheckAttendances->contains($budBashCheckAttendance)) {
            $this->budBashCheckAttendances->add($budBashCheckAttendance);
            $budBashCheckAttendance->setBudBashParty($this);
        }

        return $this;
    }

    public function removeBudBashCheckAttendance(BudBashCheckAttendance $budBashCheckAttendance): static
    {
        if ($this->budBashCheckAttendances->removeElement($budBashCheckAttendance)) {
            // set the owning side to null (unless already changed)
            if ($budBashCheckAttendance->getBudBashParty() === $this) {
                $budBashCheckAttendance->setBudBashParty(null);
            }
        }

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): static
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getExtraInfo(): ?string
    {
        return $this->extraInfo;
    }

    public function setExtraInfo(?string $extraInfo): static
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }
}
