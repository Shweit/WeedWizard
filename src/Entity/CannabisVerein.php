<?php

namespace App\Entity;

use App\Repository\CannabisVereinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CannabisVereinRepository::class)]
class CannabisVerein
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $mitgliedsbeitrag = null;

    #[ORM\Column(length: 1023, nullable: true)]
    private ?string $beschreibung = null;

    #[ORM\Column(length: 1023, nullable: true)]
    private ?string $sonstiges = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'cannabisVereine')]
    private Collection $mitglieder;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $coordinaten = null;

    #[ORM\ManyToOne(inversedBy: 'erstellteVereine')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $erstelltVon = null;

    public function __construct()
    {
        $this->mitglieder = new ArrayCollection();
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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getMitgliedsbeitrag(): ?string
    {
        return $this->mitgliedsbeitrag;
    }

    public function setMitgliedsbeitrag(string $mitgliedsbeitrag): static
    {
        $this->mitgliedsbeitrag = $mitgliedsbeitrag;

        return $this;
    }

    public function getBeschreibung(): ?string
    {
        return $this->beschreibung;
    }

    public function setBeschreibung(?string $beschreibung): static
    {
        $this->beschreibung = $beschreibung;

        return $this;
    }

    public function getSonstiges(): ?string
    {
        return $this->sonstiges;
    }

    public function setSonstiges(?string $sonstiges): static
    {
        $this->sonstiges = $sonstiges;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMitglieder(): Collection
    {
        return $this->mitglieder;
    }

    public function addMitglieder(User $mitglieder): static
    {
        if (!$this->mitglieder->contains($mitglieder)) {
            $this->mitglieder->add($mitglieder);
        }

        return $this;
    }

    public function removeMitglieder(User $mitglieder): static
    {
        $this->mitglieder->removeElement($mitglieder);

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCoordinaten(): ?string
    {
        return $this->coordinaten;
    }

    public function setCoordinaten(string $coordinaten): static
    {
        $this->coordinaten = $coordinaten;

        return $this;
    }

    public function getErstelltVon(): ?User
    {
        return $this->erstelltVon;
    }

    public function setErstelltVon(?User $erstelltVon): static
    {
        $this->erstelltVon = $erstelltVon;

        return $this;
    }
}
