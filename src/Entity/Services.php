<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 450)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detailedDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaKeywords = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTags = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaAuthor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDetailedDescription(): ?string
    {
        return $this->detailedDescription;
    }

    public function setDetailedDescription(?string $detailedDescription): static
    {
        $this->detailedDescription = $detailedDescription;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): static
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getMetaTags(): ?string
    {
        return $this->metaTags;
    }

    public function setMetaTags(?string $metaTags): static
    {
        $this->metaTags = $metaTags;

        return $this;
    }

    public function getMetaAuthor(): ?string
    {
        return $this->metaAuthor;
    }

    public function setMetaAuthor(?string $metaAuthor): static
    {
        $this->metaAuthor = $metaAuthor;

        return $this;
    }
}
