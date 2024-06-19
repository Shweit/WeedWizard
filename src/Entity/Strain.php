<?php

namespace App\Entity;

use App\Repository\StrainRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StrainRepository::class)]
class Strain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate', 'cannastrainLibrary'])]
    private ?string $seedfinder_id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['cannastrainLibrary'])]
    private array $breeder_info = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['cannastrainLibrary'])]
    private array $parents = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['cannastrainLibrary'])]
    private array $hybrids = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['cannastrainLibrary'])]
    private array $medical = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['cannastrainLibrary'])]
    private array $pics = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['cannastrainLibrary'])]
    private array $reviews = [];

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'strains')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Breeder $breeder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeedfinderId(): ?string
    {
        return $this->seedfinder_id;
    }

    public function setSeedfinderId(string $seedfinder_id): static
    {
        $this->seedfinder_id = $seedfinder_id;

        return $this;
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

    public function getBreederInfo(): array
    {
        return $this->breeder_info;
    }

    public function setBreederInfo(array $breeder_info): static
    {
        $this->breeder_info = $breeder_info;

        return $this;
    }

    public function getParents(): array
    {
        return $this->parents;
    }

    public function setParents(array $parents): static
    {
        $this->parents = $parents;

        return $this;
    }

    public function getHybrids(): array
    {
        return $this->hybrids;
    }

    public function setHybrids(array $hybrids): static
    {
        $this->hybrids = $hybrids;

        return $this;
    }

    public function getMedical(): array
    {
        return $this->medical;
    }

    public function setMedical(array $medical): static
    {
        $this->medical = $medical;

        return $this;
    }

    public function getPics(): array
    {
        return $this->pics;
    }

    public function setPics(array $pics): static
    {
        $this->pics = $pics;

        return $this;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }

    public function setReviews(array $reviews): static
    {
        $this->reviews = $reviews;

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
}
