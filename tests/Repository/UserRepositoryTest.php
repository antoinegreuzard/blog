<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testFind(): void
    {
        // Setup a user for testing
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username1');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $foundUser = $this->userRepository->find($user->getId());
        $this->assertInstanceOf(User::class, $foundUser);
    }

    public function testFindOneBy(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $foundUser = $this->userRepository->findOneBy(['email' => 'user@example.com']);
        $this->assertInstanceOf(User::class, $foundUser);
    }

    public function testFindAll(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username3');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $users = $this->userRepository->findAll();
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
    }

    public function testFindBy(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password123');
        $user->setUsername('username4');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $users = $this->userRepository->findBy(['email' => 'user@example.com']);
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    public function testUpgradePassword(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('old_password');
        $user->setUsername('testuser');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $newHashedPassword = 'new_hashed_password';
        $this->userRepository->upgradePassword($user, $newHashedPassword);

        $this->assertEquals($newHashedPassword, $user->getPassword());
    }

    public function testUpgradePasswordWithUnsupportedUser(): void
    {
        $this->expectException(UnsupportedUserException::class);

        $unsupportedUser = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $this->userRepository->upgradePassword($unsupportedUser, 'new_hashed_password');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
