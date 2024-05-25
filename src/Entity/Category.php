<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostOperation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  description: "Resource for managing categories",
  operations: [
    new Get(normalizationContext: ['groups' => 'category:item']),
    new GetCollection(normalizationContext: ['groups' => 'category:list']),
    new PostOperation(denormalizationContext: ['groups' => 'category:write']),
    new Put(denormalizationContext: ['groups' => 'category:write']),
    new Delete()
  ],
  order: ["name" => "ASC"],
  paginationEnabled: false
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['category:list', 'category:item'])]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups(['category:list', 'category:item', 'category:write'])]
  private ?string $name = null;

  #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'category')]
  private Collection $posts;

  public function __construct()
  {
    $this->posts = new ArrayCollection();
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

  /**
   * @return Collection<int, Post>
   */
  public function getPosts(): Collection
  {
    return $this->posts;
  }

  public function addPost(Post $post): static
  {
    if (!$this->posts->contains($post)) {
      $this->posts->add($post);
      $post->setCategory($this);
    }

    return $this;
  }

  public function removePost(Post $post): static
  {
    if ($this->posts->removeElement($post)) {
      // set the owning side to null (unless already changed)
      if ($post->getCategory() === $this) {
        $post->setCategory(null);
      }
    }

    return $this;
  }
}  