<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PostRepositoryTest
 *
 * Cette classe contient les tests unitaires pour le `PostRepository`.
 * Elle étend `KernelTestCase` pour fournir un conteneur de service et un gestionnaire d'entité Doctrine.
 */
class PostRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface $entityManager
     * Le gestionnaire d'entité utilisé pour interagir avec la base de données de test.
     */
    private $entityManager;

    /**
     * @var PostRepository $postRepository
     * Le repository pour l'entité Post utilisé pour tester les opérations de récupération de données.
     */
    private PostRepository $postRepository;

    /**
     * testFind
     *
     * Teste la méthode find() du repository pour récupérer un post par son ID.
     * Crée une catégorie et un utilisateur nécessaires pour le post.
     */
    public function testFind(): void
    {
        $category = new Category();
        $category->setName('Sample Category');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail(uniqid('user_', true) . '@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Sample Post');
        $post->setContent('This is a sample post.');
        $post->setSlug(uniqid('sample-post_', true));
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $foundPost = $this->postRepository->find($post->getId());
        $this->assertInstanceOf(Post::class, $foundPost);
    }

    /**
     * testFindOneBy
     *
     * Teste la méthode findOneBy() pour récupérer un post selon un critère spécifique.
     * Crée une catégorie et un utilisateur nécessaires pour le post.
     */
    public function testFindOneBy(): void
    {
        $category = new Category();
        $category->setName('Sample Category');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail(uniqid('user_', true) . '@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Sample Post');
        $post->setContent('This is a sample post.');
        $post->setSlug(uniqid('sample-post_', true));
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $foundPost = $this->postRepository->findOneBy(['title' => 'Sample Post']);
        $this->assertInstanceOf(Post::class, $foundPost);
        $this->assertEquals('Sample Post', $foundPost->getTitle());
    }

    /**
     * testFindAll
     *
     * Teste la méthode findAll() pour récupérer tous les posts.
     * Crée une catégorie et un utilisateur nécessaires pour les posts.
     * Vérifie que la liste des posts retournée n'est pas vide.
     */
    public function testFindAll(): void
    {
        $category = new Category();
        $category->setName('Sample Category');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail(uniqid('user_', true) . '@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Sample Post');
        $post->setContent('This is a sample post.');
        $post->setSlug(uniqid('sample-post_', true));
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $posts = $this->postRepository->findAll();
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
    }

    /**
     * testFindBy
     *
     * Teste la méthode findBy() pour récupérer des posts selon un critère spécifique.
     * Crée une catégorie et un utilisateur nécessaires pour les posts.
     * Vérifie que la liste des posts avec la catégorie 'Technology' n'est pas vide.
     */
    public function testFindBy(): void
    {
        $category = new Category();
        $category->setName('Technology');
        $this->entityManager->persist($category);

        $user = new User();
        $user->setEmail(uniqid('user_', true) . '@example.com');
        $user->setPassword('password123');
        $user->setUsername('username');
        $this->entityManager->persist($user);

        $post = new Post();
        $post->setTitle('Tech Post');
        $post->setContent('This is a post about technology.');
        $post->setSlug(uniqid('tech-post_', true));
        $post->setCategory($category);
        $post->setAuthor($user);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $posts = $this->postRepository->findBy(
            ['category' => $category->getId()]
        );
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
        $this->assertInstanceOf(Post::class, $posts[0]);
    }

    /**
     * setUp
     *
     * Méthode exécutée avant chaque test. Elle initialise le gestionnaire d'entité et le repository.
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->postRepository = $this->entityManager->getRepository(
            Post::class
        );
    }

    /**
     * tearDown
     *
     * Méthode exécutée après chaque test. Elle ferme le gestionnaire d'entité pour éviter les fuites de mémoire.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // éviter les fuites de mémoire
    }
}
