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
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The Category PHP class under namespace App\Entity.
 * Annotated with ORM Entity, it represents the Category entity in the Database.
 * It also identifies the CategoryRepository class with repositoryClass property
 * as specific repository for Category Entity.
 * It also maps the Category entity with '@ApiResource' annotation that
 * describes how to process it through API Platform.
 * This annotation defines various operations including GET, POST, PUT, DELETE,
 * PATCH, enacted on the Category objects through API.
 * All the operations have their URI Templates and they're associated with
 * OpenAPI context including summary, descriptions, request body contents and
 * responses.
 * For POST, PUT, and PATCH operations, a security access layer is also defined
 * to permit only User Role to perform these operations.
 * ApiResource annotation also describes normalizationContext,
 * denormalizationContext, OpenAPI context with properties schema of Category
 * entity, and pagination settings.
 * Class properties: id, name, posts are defined private by default.
 * PHP DocBlocks are used for main constructors and methods of Category class to
 * specify return types and parameters.
 * 'Groups' are applied to properties to include in the normalization and
 * denormalization process.
 * The posts property is particularly interesting, as it signifies the OneToMany
 * relationship with the Post entity.
 * A constructor method is defined initializing the posts property as an
 * ArrayCollection.
 * Getter and Setter methods are also provided for the Category properties
 * including posts.
 * AddPost and removePost methods are used to manage the posts collection.
 * This class provides a comprehensive blueprint to handle Category type objects
 * within the application environment.
 */
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/categories/{id}',
            openapiContext: [
                'summary' => 'Get a category',
                'description' => 'Retrieve a category by its ID.',
                'responses' => [
                    '200' => [
                        'description' => 'Category resource',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 1,
                                    'name' => 'Technology',
                                    'posts' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ),
        new GetCollection(
            uriTemplate: '/categories',
            openapiContext: [
                'summary' => 'Get the collection of categories',
                'description' => 'Retrieve a list of categories.',
                'responses' => [
                    '200' => [
                        'description' => 'Categories collection',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    [
                                        'id' => 1,
                                        'name' => 'Technology',
                                        'posts' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ),
        new ApiPost(
            uriTemplate: '/categories',
            openapiContext: [
                'summary' => 'Create a new category',
                'description' => 'Create a new category with the provided details.',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'example' => [
                                'name' => 'Health',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Category created',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 2,
                                    'name' => 'Health',
                                    'posts' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Put(
            uriTemplate: '/categories/{id}',
            openapiContext: [
                'summary' => 'Update a category',
                'description' => 'Update the details of an existing category.',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'example' => [
                                'name' => 'Science',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Category updated',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 1,
                                    'name' => 'Science',
                                    'posts' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Delete(
            uriTemplate: '/categories/{id}',
            openapiContext: [
                'summary' => 'Delete a category',
                'description' => 'Delete a category by its ID.',
                'responses' => [
                    '204' => [
                        'description' => 'Category deleted',
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
        new Patch(
            uriTemplate: '/categories/{id}',
            openapiContext: [
                'summary' => 'Partially update a category',
                'description' => 'Update some details of an existing category.',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'example' => [
                                'name' => 'Art',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Category partially updated',
                        'content' => [
                            'application/json' => [
                                'example' => [
                                    'id' => 1,
                                    'name' => 'Art',
                                    'posts' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            security: "is_granted('ROLE_USER')",
        ),
    ],
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']],
    openapiContext: [
        'components' => [
            'schemas' => [
                'Category' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                            'example' => 1,
                        ],
                        'name' => [
                            'type' => 'string',
                            'example' => 'Technology',
                        ],
                        'posts' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'id' => [
                                        'type' => 'integer',
                                        'example' => 1,
                                    ],
                                    'title' => [
                                        'type' => 'string',
                                        'example' => 'My first post',
                                    ],
                                    'content' => [
                                        'type' => 'string',
                                        'example' => 'This is the content of my first post.',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    paginationEnabled: false
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['category:read', 'category:write'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'category', orphanRemoval: true)]
    #[Groups(['category:read'])]
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
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

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
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
