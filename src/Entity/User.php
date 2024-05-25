<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostOperation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  description: "Resource for managing users",
  operations: [
    new Get(normalizationContext: ['groups' => 'user:item']),
    new GetCollection(normalizationContext: ['groups' => 'user:list']),
    new PostOperation(denormalizationContext: ['groups' => 'user:write']),
    new Put(denormalizationContext: ['groups' => 'user:write']),
    new Delete()
  ],
  paginationEnabled: true
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['user:list', 'user:item'])]
  private ?int $id = null;

  #[ORM\Column(length: 180, unique: true)]
  #[Groups(['user:list', 'user:item', 'user:write'])]
  private ?string $email = null;

  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string|null The hashed password
   */
  #[ORM\Column]
  private ?string $password = null;

  #[ORM\Column(length: 255)]
  #[Groups(['user:list', 'user:item', 'user:write'])]
  private ?string $username = null;

  #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author', orphanRemoval: true)]
  private Collection $posts;

  public function __construct()
  {
    $this->posts = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string)$this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): static
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials(): void
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getUsername(): ?string
  {
    return $this->username;
  }

  public function setUsername(string $username): static
  {
    $this->username = $username;

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
      $post->setAuthor($this);
    }

    return $this;
  }

  public function removePost(Post $post): static
  {
    if ($this->posts->removeElement($post)) {
      // set the owning side to null (unless already changed)
      if ($post->getAuthor() === $this) {
        $post->setAuthor(null);
      }
    }

    return $this;
  }
}