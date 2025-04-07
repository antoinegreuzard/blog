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

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Class UserRepositoryTest
 *
 * Cette classe contient les tests unitaires pour le `UserRepository`.
 * Elle utilise PHPUnit pour vérifier les comportements de base de l'entité `User` dans le contexte du repository.
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface $entityManager
     * Le gestionnaire d'entité utilisé pour interagir avec la base de données de test.
     */
    private $entityManager;

    /**
     * @var UserRepository $userRepository
     * Le repository pour l'entité User utilisé pour tester les opérations de récupération et de gestion des données.
     */
    private UserRepository $userRepository;

    /**
     * testFind
     *
     * Teste la méthode find() du repository pour récupérer un utilisateur par son ID.
     * Crée et persiste un utilisateur pour les besoins du test.
     */
    public function testFind(): void
    {
        $user = new User();
        $user->setEmail(uniqid('user_', true).'@example.com');
        $user->setPassword('SecurePass123!');
        $user->setUsername('username1');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $foundUser = $this->userRepository->find($user->getId());
        $this->assertInstanceOf(User::class, $foundUser);
    }

    /**
     * testFindOneBy
     *
     * Teste la méthode findOneBy() pour récupérer un utilisateur selon un critère spécifique.
     * Crée et persiste un utilisateur pour les besoins du test.
     */
    public function testFindOneBy(): void
    {
        $user = new User();
        $user->setEmail(uniqid('user_', true).'@example.com');
        $user->setPassword('SecurePass123!');
        $user->setUsername('username2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $foundUser = $this->userRepository->findOneBy(
            ['email' => 'user@example.com']
        );
        $this->assertInstanceOf(User::class, $foundUser);
    }

    /**
     * testFindAll
     *
     * Teste la méthode findAll() pour récupérer tous les utilisateurs.
     * Crée et persiste un utilisateur pour les besoins du test.
     * Vérifie que la liste des utilisateurs retournée n'est pas vide.
     */
    public function testFindAll(): void
    {
        $user = new User();
        $user->setEmail(uniqid('user_', true).'@example.com');
        $user->setPassword('SecurePass123!');
        $user->setUsername('username3');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $users = $this->userRepository->findAll();
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
    }

    /**
     * testFindBy
     *
     * Teste la méthode findBy() pour récupérer des utilisateurs selon un critère spécifique.
     * Crée et persiste un utilisateur pour les besoins du test.
     * Vérifie que la liste des utilisateurs retournée n'est pas vide.
     */
    public function testFindBy(): void
    {
        $user = new User();
        $user->setEmail(uniqid('user_', true).'@example.com');
        $user->setPassword('SecurePass123!');
        $user->setUsername('username4');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $users = $this->userRepository->findBy(['email' => 'user@example.com']);
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    /**
     * testUpgradePassword
     *
     * Teste la méthode upgradePassword() pour mettre à jour le mot de passe d'un utilisateur.
     * Crée et persiste un utilisateur avec un ancien mot de passe, puis met à jour le mot de passe.
     */
    public function testUpgradePassword(): void
    {
        $user = new User();
        $user->setEmail(uniqid('user_', true).'@example.com');
        $user->setPassword('OldSecurePass456!');
        $user->setUsername('testuser');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $newHashedPassword = 'NewSecurePass789!';
        $this->userRepository->upgradePassword($user, $newHashedPassword);

        $this->assertEquals($newHashedPassword, $user->getPassword());
    }

    /**
     * testUpgradePasswordWithUnsupportedUser
     *
     * Teste la méthode upgradePassword() avec un utilisateur non supporté.
     * Vérifie que l'exception UnsupportedUserException est levée.
     */
    public function testUpgradePasswordWithUnsupportedUser(): void
    {
        $this->expectException(UnsupportedUserException::class);

        $unsupportedUser = $this->createMock(
            PasswordAuthenticatedUserInterface::class
        );
        $this->userRepository->upgradePassword(
            $unsupportedUser,
            'NewSecurePass789!'
        );
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
        $this->userRepository = $this->entityManager->getRepository(
            User::class
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
