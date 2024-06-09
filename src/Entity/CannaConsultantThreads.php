<?php

namespace App\Entity;

use App\Repository\CannaConsultantThreadsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CannaConsultantThreadsRepository::class)]
class CannaConsultantThreads
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private array $thread = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getThread(): array
    {
        return $this->thread;
    }

    public function setThread(array $thread): static
    {
        $this->thread = $thread;

        return $this;
    }
}
