<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryRepositoryTest
 *
 * Cette classe contient les tests unitaires pour le `CategoryRepository`.
 * Elle étend `KernelTestCase` pour fournir un conteneur de service et un gestionnaire d'entité Doctrine.
 */
class CategoryRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface $entityManager
     * Le gestionnaire d'entité utilisé pour interagir avec la base de données de test.
     */
    private $entityManager;

    /**
     * @var CategoryRepository $categoryRepository
     * Le repository pour l'entité Category utilisé pour tester les opérations de récupération de données.
     */
    private CategoryRepository $categoryRepository;

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
        $this->categoryRepository = $this->entityManager->getRepository(Category::class);

        // Préparer les données de test
        $this->loadTestData();
    }

    /**
     * testFind
     *
     * Teste la méthode find() du repository pour récupérer une catégorie par son ID.
     * Assume qu'une catégorie avec l'ID 1 existe dans la base de données.
     */
    public function testFind(): void
    {
        $category = $this->categoryRepository->find(1);
        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * testFindOneBy
     *
     * Teste la méthode findOneBy() pour récupérer une catégorie selon un critère spécifique.
     * Ici, on assume qu'une catégorie avec le nom 'Tech' existe.
     */
    public function testFindOneBy(): void
    {
        $category = $this->categoryRepository->findOneBy(['name' => 'Tech']);
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Tech', $category->getName());
    }

    /**
     * testFindAll
     *
     * Teste la méthode findAll() pour récupérer toutes les catégories.
     * Vérifie que la liste des catégories retournée n'est pas vide.
     */
    public function testFindAll(): void
    {
        $categories = $this->categoryRepository->findAll();
        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
    }

    /**
     * testFindBy
     *
     * Teste la méthode findBy() pour récupérer des catégories selon un critère spécifique.
     * Vérifie que la liste des catégories avec le nom 'Tech' n'est pas vide.
     */
    public function testFindBy(): void
    {
        $categories = $this->categoryRepository->findBy(['name' => 'Tech']);
        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Category::class, $categories[0]);
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

    /**
     * loadTestData
     *
     * Charge les données de test dans la base de données avant chaque test.
     * Ici, une catégorie 'Tech' est créée et persistée.
     */
    private function loadTestData(): void
    {
        $category = new Category();
        $category->setName('Tech');
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}
