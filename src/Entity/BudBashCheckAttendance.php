<?php

namespace App\Entity;

use App\Repository\BudBashCheckAttendanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudBashCheckAttendanceRepository::class)]
class BudBashCheckAttendance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'budBashCheckAttendances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BudBash $BudBashParty = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $participant = null;

    #[ORM\Column]
    private ?bool $checked_attendance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBudBashParty(): ?BudBash
    {
        return $this->BudBashParty;
    }

    public function setBudBashParty(?BudBash $BudBashParty): static
    {
        $this->BudBashParty = $BudBashParty;

        return $this;
    }

    public function getParticipant(): ?User
    {
        return $this->participant;
    }

    public function setParticipant(?User $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function isCheckedAttendance(): ?bool
    {
        return $this->checked_attendance;
    }

    public function setCheckedAttendance(bool $checked_attendance): static
    {
        $this->checked_attendance = $checked_attendance;

        return $this;
    }
}
