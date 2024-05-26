<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post as ApiPost;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  operations: [
    new Get(
      security: "is_granted('ROLE_USER')",
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
      security: "is_granted('ROLE_USER')",
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
      security: "is_granted('ROLE_ADMIN')",
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
    ),
    new Put(
      security: "is_granted('ROLE_ADMIN')",
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
    ),
    new Delete(
      security: "is_granted('ROLE_ADMIN')",
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
    ),
    new Patch(
      security: "is_granted('ROLE_ADMIN')",
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

  #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'category', orphanRemoval: true)]
  #[Groups(['category:read'])]
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