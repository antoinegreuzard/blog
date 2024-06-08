<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post as ApiPost;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This is the User entity class.
 *
 * It implements the UserInterface and PasswordAuthenticatedUserInterface
 * provided by Symfony, for user's authentication.
 *
 * The User has several properties: id, email, roles, password, username,
 * and posts.
 *
 * - The 'id' is an integer, which is automatically generated as the user is
 * persisted in the database.
 *   It's primarily used to identify a user in the database and cannot be set
 * manually.
 *
 * - The 'email' is a string that must be a valid email address.
 * It is used as the user identifier for authentication.
 *
 * - The 'roles' is an array of strings containing the roles of the user.
 * By default, every user has at least the 'ROLE_USER' role.
 *
 * - The 'password' is a string that contains the hashed password of the user.
 *
 * - The 'username' is a string that represents the user's username. It is used
 * for displaying purposes.
 *
 * - The 'posts' is a Collection of Post entities authored by the user.
 *   It uses the Doctrine common collections to enable complex array
 *   manipulations.
 *
 * The User entity uses various annotations:
 *
 * - The class-level ApiResource annotation is used by the API platform to
 * determine the available operations for a User.
 *
 * - The ORM\Entity annotation tells Doctrine that this class is a Doctrine
 * entity that can be persisted to the database.
 *
 * - The UniqueEntity annotation is used by the Symfony validator to ensure
 * that no two Users share the same email.
 *
 * The operations of the User entity are defined in the ApiResource annotation:
 *
 * - The Get method allows retrieving a user by their ID.
 *
 * - The GetCollection method allows retrieving a list of all users.
 *
 * - The ApiPost method allows creating a new user.
 *
 * - The Put method allows updating the details of an existing user.
 *
 * - The Delete method allows deleting a user by their ID.
 *
 * - The Patch method allows partially updating the details of an existing user.
 */
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
#[UniqueEntity(
    fields: ['email'],
    message: 'There is already an account with this email'
)]
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

    /**
     * @var array<int, string>
     */
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

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(
        targetEntity: Post::class,
        mappedBy: 'author',
        orphanRemoval: true
    )]
    #[Groups(['user:read'])]
    private Collection $posts;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return array
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<int, string> $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return void
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
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

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
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
