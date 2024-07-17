<?php

namespace App\Entity;

use App\Repository\KnowledgeBaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KnowledgeBaseRepository::class)]
class KnowledgeBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    private ?string $articleName = null;

    #[ORM\Column(length: 255)]
    private ?string $site = null;

    #[ORM\Column(length: 9999)]
    private ?string $ArticelContent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getArticleName(): ?string
    {
        return $this->articleName;
    }

    public function setArticleName(string $articleName): static
    {
        $this->articleName = $articleName;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getArticleContent(): ?string
    {
        return $this->ArticelContent;
    }

    public function setArticleContent(string $ArticelContent): static
    {
        $this->ArticelContent = $ArticelContent;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
