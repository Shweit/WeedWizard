<?php

namespace App\Entity;

use App\Repository\BreederRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BreederRepository::class)]
class Breeder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate', 'cannastrainLibrary'])]
    private ?string $seedfinder_id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate', 'cannastrainLibrary'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['growMate', 'cannastrainLibrary'])]
    private ?string $logo = null;

    /**
     * @var Collection<int, Strain>
     */
    #[ORM\OneToMany(targetEntity: Strain::class, mappedBy: 'breeder')]
    #[Groups(['cannastrainLibrary'])]
    private Collection $strains;

    public function __construct()
    {
        $this->strains = new ArrayCollection();
    }

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Strain>
     */
    public function getStrains(): Collection
    {
        return $this->strains;
    }

    public function addStrain(Strain $strain): static
    {
        if (!$this->strains->contains($strain)) {
            $this->strains->add($strain);
            $strain->setBreeder($this);
        }

        return $this;
    }

    public function removeStrain(Strain $strain): static
    {
        if ($this->strains->removeElement($strain)) {
            // set the owning side to null (unless already changed)
            if ($strain->getBreeder() === $this) {
                $strain->setBreeder(null);
            }
        }

        return $this;
    }
}
