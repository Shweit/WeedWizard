<?php

namespace App\Entity;

use App\Repository\UserInteractionsRepository;
use App\Service\InteractionsType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserInteractionsRepository::class)]
class UserInteractions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_interactions'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInteractions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user_interactions'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userInteractions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Blog $Post = null;

    #[ORM\Column]
    #[Groups(['user_interactions'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'string', enumType: InteractionsType::class)]
    #[Groups(['user_interactions'])]
    private ?InteractionsType $interactionType = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?Blog
    {
        return $this->Post;
    }

    public function setPost(?Blog $Post): static
    {
        $this->Post = $Post;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getInteractionType(): ?InteractionsType
    {
        return $this->interactionType;
    }

    public function setInteractionType(InteractionsType $interactionType): static
    {
        $this->interactionType = $interactionType;

        return $this;
    }
}
