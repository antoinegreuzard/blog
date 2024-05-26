<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post as ApiPost;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  operations: [
    new Get(
      uriTemplate: '/users/{id}',
      openapiContext: [
        'summary' => 'Get a user',
        'description' => 'Retrieve a user by their ID.',
        'responses' => [
          '200' => [
            'description' => 'User resource',
            'content' => [
              'application/json' => [
                'example' => [
                  'id' => 1,
                  'email' => 'johndoe@example.com',
                  'roles' => ['ROLE_USER'],
                  'username' => 'johndoe',
                  'posts' => [],
                ],
              ],
            ],
          ],
        ],
      ],
      security: "is_granted('ROLE_USER') and object == user",
    ),
    new GetCollection(
      uriTemplate: '/users',
      openapiContext: [
        'summary' => 'Get the collection of users',
        'description' => 'Retrieve a list of users.',
        'responses' => [
          '200' => [
            'description' => 'Users collection',
            'content' => [
              'application/json' => [
                'example' => [
                  [
                    'id' => 1,
                    'email' => 'johndoe@example.com',
                    'roles' => ['ROLE_USER'],
                    'username' => 'johndoe',
                    'posts' => [],
                  ],
                ],
              ],
            ],
          ],
        ],
      ],
      security: "is_granted('ROLE_USER')",
    ),
    new ApiPost(
      uriTemplate: '/users',
      openapiContext: [
        'summary' => 'Create a new user',
        'description' => 'Create a new user with the provided details.',
        'requestBody' => [
          'content' => [
            'application/json' => [
              'example' => [
                'email' => 'janedoe@example.com',
                'password' => 'password123',
                'username' => 'janedoe',
              ],
            ],
          ],
        ],
        'responses' => [
          '201' => [
            'description' => 'User created',
            'content' => [
              'application/json' => [
                'example' => [
                  'id' => 2,
                  'email' => 'janedoe@example.com',
                  'roles' => ['ROLE_USER'],
                  'username' => 'janedoe',
                  'posts' => [],
                ],
              ],
            ],
          ],
        ],
      ],
    ),
    new Put(
      uriTemplate: '/users/{id}',
      openapiContext: [
        'summary' => 'Update a user',
        'description' => 'Update the details of an existing user.',
        'requestBody' => [
          'content' => [
            'application/json' => [
              'example' => [
                'email' => 'johndoe_updated@example.com',
                'username' => 'johndoe_updated',
              ],
            ],
          ],
        ],
        'responses' => [
          '200' => [
            'description' => 'User updated',
            'content' => [
              'application/json' => [
                'example' => [
                  'id' => 1,
                  'email' => 'johndoe_updated@example.com',
                  'roles' => ['ROLE_USER'],
                  'username' => 'johndoe_updated',
                  'posts' => [],
                ],
              ],
            ],
          ],
        ],
      ],
      security: "is_granted('ROLE_USER') and object == user",
    ),
    new Delete(
      uriTemplate: '/users/{id}',
      openapiContext: [
        'summary' => 'Delete a user',
        'description' => 'Delete a user by their ID.',
        'responses' => [
          '204' => [
            'description' => 'User deleted',
          ],
        ],
      ],
      security: "is_granted('ROLE_USER')",
    ),
    new Patch(
      uriTemplate: '/users/{id}',
      openapiContext: [
        'summary' => 'Partially update a user',
        'description' => 'Update some details of an existing user.',
        'requestBody' => [
          'content' => [
            'application/json' => [
              'example' => [
                'username' => 'johndoe_partial',
              ],
            ],
          ],
        ],
        'responses' => [
          '200' => [
            'description' => 'User partially updated',
            'content' => [
              'application/json' => [
                'example' => [
                  'id' => 1,
                  'email' => 'johndoe@example.com',
                  'roles' => ['ROLE_USER'],
                  'username' => 'johndoe_partial',
                  'posts' => [],
                ],
              ],
            ],
          ],
        ],
      ],
      security: "is_granted('ROLE_USER') and object == user",
    ),
  ],
  normalizationContext: ['groups' => ['user:read']],
  denormalizationContext: ['groups' => ['user:write']],
  paginationEnabled: false
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['user:read'])]
  private ?int $id = null;

  #[ORM\Column(length: 180, unique: true)]
  #[Assert\NotBlank]
  #[Assert\Email]
  #[Groups(['user:read', 'user:write'])]
  private ?string $email = null;

  #[ORM\Column]
  #[Groups(['user:read', 'user:write'])]
  private array $roles = [];

  /**
   * @var string|null The hashed password
   */
  #[ORM\Column]
  #[Assert\NotBlank]
  #[Groups(['user:write'])]
  private ?string $password = null;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank]
  #[Groups(['user:read', 'user:write'])]
  private ?string $username = null;

  #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author', orphanRemoval: true)]
  #[Groups(['user:read'])]
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

  public function setEmail(string $email): self
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

  public function setRoles(array $roles): self
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

  public function setPassword(string $password): self
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

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  /**
   * @return Collection
   */
  public function getPosts(): Collection
  {
    return $this->posts;
  }

  public function addPost(Post $post): self
  {
    if (!$this->posts->contains($post)) {
      $this->posts[] = $post;
      $post->setAuthor($this);
    }

    return $this;
  }

  public function removePost(Post $post): self
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