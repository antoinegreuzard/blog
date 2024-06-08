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
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @class Post
 * This class represents a Post entity in a blogging application
 *
 * It's decorated with #[ApiResource()], #[ORM\Entity()] and
 * #[ORM\HasLifecycleCallbacks()] attributes on declaration.
 * The entity is associated with the PostRepository class.
 *
 * Attributes:
 * - id: Primary Key, and it's auto-incremented.
 * - title: The title of the blog post. It's mandatory and its string length
 * should fall within 2 to 255 characters.
 * - content: The main content of the blog post. It's mandatory.
 * - createdAt: The date and time the blog post was created.
 * - updatedAt: The date and time the blog post was last updated.
 * - slug: The URL friendly version of the blog post title. It's mandatory and
 * unique throughout the Post table.
 * - category: The category that the blog post belongs to. It's mandatory.
 * - author: The User entity who created the blog post. It's mandatory.
 *
 * Its methods provide functionality to get and set each attribute.
 * They also manage "prePersist" and "preUpdate" lifecycle callbacks.
 *
 * Applied API operations:
 * - Get:
 *   Retrieves a single post by its ID.
 *
 * - GetCollection:
 *   Grabs a collection of posts.
 *
 * - ApiPost:
 *   Allows the creation of a new post. This operation requires the ROLE_USER
 * permission.
 *
 * - Put:
 *   Provides the capability to update an existing post. This operation also
 * requires the ROLE_USER permission.
 *
 * - Delete:
 *   Enables the deletion of a post by its ID. The operation necessitates the
 * ROLE_USER role.
 *
 * - Patch:
 *   Provides partially updating an existing post capability. This operation
 * needs the ROLE_USER permission.
 *
 * The fields 'title', 'slug', 'content', 'category' and 'author' are available
 * for both read and write operations.
 * The 'id', 'createdAt' & 'updatedAt' are read-only.
 *
 * Pagination is disabled for this entity.
 */
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/posts/{id}',
            openapiContext: [
                'summary' => 'Get a post',
                'description' => 'Retrieve a post by its ID.',
                'responses' => [
                    '200' => [
                        'description' => 'Post resource',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 1,
                                    'title' => 'My First Post',
                                    'content' => 'This is the content of my first post.',
                                    'createdAt' => '2023-01-01T12:00:00+00:00',
                                    'updatedAt' => '2023-01-01T12:00:00+00:00',
                                    'slug' => 'my-first-post',
                                    'category' => [
                                        'id' => 1,
                                        'name' => 'Technology',
                                    ],
                                    'author' => [
                                        'id' => 1,
                                        'username' => 'johndoe',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ),
        new GetCollection(
            uriTemplate: '/posts',
            openapiContext: [
                'summary' => 'Get the collection of posts',
                'description' => 'Retrieve a list of posts.',
                'responses' => [
                    '200' => [
                        'description' => 'Posts collection',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    [
                                        'id' => 1,
                                        'title' => 'My First Post',
                                        'content' => 'This is the content of my first post.',
                                        'createdAt' => '2023-01-01T12:00:00+00:00',
                                        'updatedAt' => '2023-01-01T12:00:00+00:00',
                                        'slug' => 'my-first-post',
                                        'category' => [
                                            'id' => 1,
                                            'name' => 'Technology',
                                        ],
                                        'author' => [
                                            'id' => 1,
                                            'username' => 'johndoe',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ),
        new ApiPost(
            uriTemplate: '/posts',
            openapiContext: [
                'summary' => 'Create a new post',
                'description' => 'Create a new post with the provided details.',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'example' => [
                                'title' => 'My New Post',
                                'content' => 'This is the content of my new post.',
                                'slug' => 'my-new-post',
                                'category' => '/categories/1',
                                'author' => '/users/1',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Post created',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 2,
                                    'title' => 'My New Post',
                                    'content' => 'This is the content of my new post.',
                                    'createdAt' => '2023-01-01T12:00:00+00:00',
                                    'updatedAt' => '2023-01-01T12:00:00+00:00',
                                    'slug' => 'my-new-post',
                                    'category' => [
                                        'id' => 1,
                                        'name' => 'Technology',
                                    ],
                                    'author' => [
                                        'id' => 1,
                                        'username' => 'johndoe',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Put(
            uriTemplate: '/posts/{id}',
            openapiContext: [
                'summary' => 'Update a post',
                'description' => 'Update the details of an existing post.',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'example' => [
                                'title' => 'Updated Post Title',
                                'content' => 'This is the updated content of the post.',
                                'slug' => 'updated-post-title',
                                'category' => '/categories/1',
                                'author' => '/users/1',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Post updated',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 1,
                                    'title' => 'Updated Post Title',
                                    'content' => 'This is the updated content of the post.',
                                    'createdAt' => '2023-01-01T12:00:00+00:00',
                                    'updatedAt' => '2023-01-01T12:00:00+00:00',
                                    'slug' => 'updated-post-title',
                                    'category' => [
                                        'id' => 1,
                                        'name' => 'Technology',
                                    ],
                                    'author' => [
                                        'id' => 1,
                                        'username' => 'johndoe',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Delete(
            uriTemplate: '/posts/{id}',
            openapiContext: [
                'summary' => 'Delete a post',
                'description' => 'Delete a post by its ID.',
                'responses' => [
                    '204' => [
                        'description' => 'Post deleted',
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Patch(
            uriTemplate: '/posts/{id}',
            openapiContext: [
                'summary' => 'Partially update a post',
                'description' => 'Update some details of an existing post.',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'example' => [
                                'title' => 'Partially Updated Post Title',
                                'content' => 'This is the partially updated content of the post.',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Post partially updated',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 1,
                                    'title' => 'Partially Updated Post Title',
                                    'content' => 'This is the partially updated content of the post.',
                                    'createdAt' => '2023-01-01T12:00:00+00:00',
                                    'updatedAt' => '2023-01-01T12:00:00+00:00',
                                    'slug' => 'partially-updated-post-title',
                                    'category' => [
                                        'id' => 1,
                                        'name' => 'Technology',
                                    ],
                                    'author' => [
                                        'id' => 1,
                                        'username' => 'johndoe',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
    ],
    normalizationContext: ['groups' => ['post:read']],
    denormalizationContext: ['groups' => ['post:write']],
    paginationEnabled: false
)]
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['post:read', 'post:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(['post:read', 'post:write'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['post:read'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['post:read'])]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Groups(['post:read', 'post:write'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read', 'post:write'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read', 'post:write'])]
    private ?User $author = null;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     *
     * @return $this
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     *
     * @return $this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return void
     */
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new DateTimeImmutable();
        }

        if (null === $this->updatedAt) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    /**
     * @return void
     */
    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
