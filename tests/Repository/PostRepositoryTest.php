<?php

namespace App\Tests\Repository;

use App\Entity\Post;
use App\Entity\Category;

// Assurez-vous d'importer la classe Category
use App\Entity\User;

// Assurez-vous d'importer la classe User
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private PostRepository $postRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->postRepository = $this->entityManager->getRepository(Post::class);
    }

    public function testFind(): void
    {
        $category = new Category();
        $category->setName('Sample Category');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Sample Post');
        $post->setContent('This is a sample post.');
        $post->setSlug('sample-post');
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $foundPost = $this->postRepository->find($post->getId());
        $this->assertInstanceOf(Post::class, $foundPost);
    }

    public function testFindOneBy(): void
    {
        $category = new Category();
        $category->setName('Sample Category');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Sample Post');
        $post->setContent('This is a sample post.');
        $post->setSlug('sample-post');
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $foundPost = $this->postRepository->findOneBy(['title' => 'Sample Post']);
        $this->assertInstanceOf(Post::class, $foundPost);
        $this->assertEquals('Sample Post', $foundPost->getTitle());
    }

    public function testFindAll(): void
    {
        $category = new Category();
        $category->setName('Sample Category');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Sample Post');
        $post->setContent('This is a sample post.');
        $post->setSlug('sample-post');
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $posts = $this->postRepository->findAll();
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
    }

    public function testFindBy(): void
    {
        $category = new Category();
        $category->setName('Technology');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Tech Post');
        $post->setContent('This is a post about technology.');
        $post->setSlug('tech-post');
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $posts = $this->postRepository->findBy(['category' => $category->getId()]);
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
        $this->assertInstanceOf(Post::class, $posts[0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
