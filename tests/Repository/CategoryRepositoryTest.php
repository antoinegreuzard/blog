<?php

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->categoryRepository = $this->entityManager->getRepository(Category::class);

        // Assurez-vous que la base de données de test est vide avant d'ajouter des catégories
        $this->entityManager->createQuery('DELETE FROM App\Entity\Category')->execute();

        // Ajoutez des catégories pour les tests
        $category1 = new Category();
        $category1->setName('Tech');
        $this->entityManager->persist($category1);

        $category2 = new Category();
        $category2->setName('Lifestyle');
        $this->entityManager->persist($category2);

        $this->entityManager->flush();
    }

    public function testFind(): void
    {
        $category = $this->categoryRepository->find(1);
        $this->assertInstanceOf(Category::class, $category);
    }

    public function testFindOneBy(): void
    {
        $category = $this->categoryRepository->findOneBy(['name' => 'Tech']);
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Tech', $category->getName());
    }

    public function testFindAll(): void
    {
        $categories = $this->categoryRepository->findAll();
        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
    }

    public function testFindBy(): void
    {
        $categories = $this->categoryRepository->findBy(['name' => 'Tech']);
        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Category::class, $categories[0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
