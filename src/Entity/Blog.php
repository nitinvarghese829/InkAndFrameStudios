<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_BLOG_SLUG', columns: ['slug'])]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::BLOB)]
    private $blogImage;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Admin $createdBy = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaKeywords = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tags = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author = null;

    /**
     * @var Collection<int, Faqs>
     */
    #[ORM\OneToMany(targetEntity: Faqs::class, mappedBy: 'blog', cascade: ['persist'], orphanRemoval: true)]
    private Collection $faqs;

    #[ORM\Column(length: 255)]
    private ?string $blogAuthor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $faqSchema = null;

    public function __construct()
    {
        $this->faqs = new ArrayCollection();
    }

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getBlogImage(): ?string
    {
        // If it's stored as a stream in DB, we need to read it
        if (is_resource($this->blogImage)) {
            return stream_get_contents($this->blogImage);
        }

        return $this->blogImage;
    }

    public function setBlogImage($blogImage): static
    {
        $this->blogImage = $blogImage;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?Admin
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Admin $createdBy): static
    {
        $this->createdBy = $createdBy;

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

    public function getBlogImageHtml(): ?string
    {
        if (!$this->blogImage) {
            return null;
        }

        if (is_resource($this->blogImage)) {
            // dd(stream_get_contents($this->blogImage));
            return stream_get_contents($this->blogImage);
        }

        dd(is_string($this->blogImage));
        return $data;
        // $base64 = ($data);

        // return sprintf('<img src="data:image/jpeg;base64,%s" style="max-height:150px; border:1px solid #ccc;" />', $base64);
    }

    public function getBlogImageDataUrl(): ?string
    {
        if (is_resource($this->blogImage)) {
            $imageData = stream_get_contents($this->blogImage);
            if ($imageData === false) {
                return null;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageData);

            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }

        return null;
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

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Faqs>
     */
    public function getFaqs(): Collection
    {
        return $this->faqs;
    }

    public function addFaq(Faqs $faq): static
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs->add($faq);
            $faq->setBlog($this);
        }

        return $this;
    }

    public function removeFaq(Faqs $faq): static
    {
        if ($this->faqs->removeElement($faq)) {
            // set the owning side to null (unless already changed)
            if ($faq->getBlog() === $this) {
                $faq->setBlog(null);
            }
        }

        return $this;
    }

    public function getBlogAuthor(): ?string
    {
        return $this->blogAuthor;
    }

    public function setBlogAuthor(string $blogAuthor): static
    {
        $this->blogAuthor = $blogAuthor;

        return $this;
    }

    public function getFaqSchema(): ?string
    {
        return $this->faqSchema;
    }

    public function setFaqSchema(?string $faqSchema): static
    {
        $this->faqSchema = $faqSchema;

        return $this;
    }
}
