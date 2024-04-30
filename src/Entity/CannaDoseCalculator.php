<?php

namespace App\Entity;

use App\Repository\CannaDoseCalculatorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CannaDoseCalculatorRepository::class)]
class CannaDoseCalculator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $experience = null;

    #[ORM\Column]
    private ?float $intensity = null;

    #[ORM\Column]
    private ?float $recommended_dosage = null;

    #[ORM\Column]
    private ?int $basis_dosage = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExperience(): ?float
    {
        return $this->experience;
    }

    public function setExperience(float $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getIntensity(): ?float
    {
        return $this->intensity;
    }

    public function setIntensity(float $intensity): static
    {
        $this->intensity = $intensity;

        return $this;
    }

    public function getRecommendedDosage(): ?float
    {
        return $this->recommended_dosage;
    }

    public function setRecommendedDosage(float $recommended_dosage): static
    {
        $this->recommended_dosage = $recommended_dosage;

        return $this;
    }

    public function getBasisDosage(): ?int
    {
        return $this->basis_dosage;
    }

    public function setBasisDosage(int $basis_dosage): static
    {
        $this->basis_dosage = $basis_dosage;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'experience' => $this->experience,
            'intensity' => $this->intensity,
            'basis_dosage' => $this->basis_dosage,
            'recommended_dosage' => $this->recommended_dosage,
            'created_at' => $this->created_at->format('Y-m-d H:i:s') ?? '',
        ];
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
}
