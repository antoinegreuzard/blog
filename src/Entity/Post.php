<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post as ApiPost;
use ApiPlatform\Metadata\Put;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['post:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
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

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }

        if (null === $this->updatedAt) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
