<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlantRepository::class)]
class Plant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['growMate'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate'])]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate'])]
    private ?string $placeOfCultivation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate'])]
    private ?string $lighting = null;

    #[ORM\ManyToOne(inversedBy: 'plants')]
    private ?User $user = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $thread = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['growMate'])]
    private ?Strain $strain = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['growMate'])]
    private ?Breeder $breeder = null;

    #[ORM\Column]
    #[Groups(['growMate'])]
    private ?int $growth = null;

    #[ORM\Column]
    private array $weekly_tasks = [];

    #[ORM\Column(nullable: true)]
    private ?int $currentPrognosisValue = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPlaceOfCultivation(): ?string
    {
        return $this->placeOfCultivation;
    }

    public function setPlaceOfCultivation(string $placeOfCultivation): static
    {
        $this->placeOfCultivation = $placeOfCultivation;

        return $this;
    }

    public function getLighting(): ?string
    {
        return $this->lighting;
    }

    public function setLighting(string $lighting): static
    {
        $this->lighting = $lighting;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getThread(): ?array
    {
        return $this->thread;
    }

    public function setThread(?array $thread): static
    {
        $this->thread = $thread;

        return $this;
    }

    public function getStrain(): ?Strain
    {
        return $this->strain;
    }

    public function setStrain(?Strain $strain): static
    {
        $this->strain = $strain;

        return $this;
    }

    public function getBreeder(): ?Breeder
    {
        return $this->breeder;
    }

    public function setBreeder(?Breeder $breeder): static
    {
        $this->breeder = $breeder;

        return $this;
    }

    public function getGrowth(): ?int
    {
        return $this->growth;
    }

    public function setGrowth(int $growth): static
    {
        $this->growth = $growth;

        return $this;
    }

    public function getWeeklyTasks(): array
    {
        return $this->weekly_tasks;
    }

    public function setWeeklyTasks(array $weekly_tasks): static
    {
        $this->weekly_tasks = $weekly_tasks;

        return $this;
    }

    public function getCurrentPrognosisValue(): ?int
    {
        return $this->currentPrognosisValue;
    }

    public function setCurrentPrognosisValue(?int $currentPrognosisValue): static
    {
        $this->currentPrognosisValue = $currentPrognosisValue;

        return $this;
    }
}
