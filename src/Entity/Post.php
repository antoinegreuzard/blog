<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostOperation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ApiResource(
  description: "Resource for managing posts",
  operations: [
    new Get(normalizationContext: ['groups' => 'post:item']),
    new GetCollection(normalizationContext: ['groups' => 'post:list']),
    new PostOperation(denormalizationContext: ['groups' => 'post:write']),
    new Put(denormalizationContext: ['groups' => 'post:write']),
    new Delete()
  ],
  order: ["createdAt" => "DESC"],
  paginationEnabled: true
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'category.name' => 'exact'])]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['post:list', 'post:item'])]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups(['post:list', 'post:item', 'post:write'])]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Groups(['post:list', 'post:item', 'post:write'])]
  private ?string $content = null;

  #[ORM\Column]
  #[Groups(['post:list', 'post:item'])]
  private ?DateTimeImmutable $createdAt = null;

  #[ORM\Column]
  #[Groups(['post:list', 'post:item'])]
  private ?DateTimeImmutable $updatedAt = null;

  #[ORM\Column(length: 255)]
  #[Groups(['post:list', 'post:item', 'post:write'])]
  private ?string $slug = null;

  #[ORM\ManyToOne(inversedBy: 'posts')]
  #[ORM\JoinColumn(nullable: false)]
  #[Groups(['post:list', 'post:item', 'post:write'])]
  private ?Category $category = null;

  #[ORM\ManyToOne(inversedBy: 'posts')]
  #[ORM\JoinColumn(nullable: false)]
  #[Groups(['post:list', 'post:item', 'post:write'])]
  private ?User $author = null;

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

  public function getCreatedAt(): ?DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?DateTimeImmutable
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(DateTimeImmutable $updatedAt): static
  {
    $this->updatedAt = $updatedAt;

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

  public function getCategory(): ?Category
  {
    return $this->category;
  }

  public function setCategory(?Category $category): static
  {
    $this->category = $category;

    return $this;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): static
  {
    $this->author = $author;

    return $this;
  }
}