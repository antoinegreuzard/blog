<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Entity;

use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * Cette classe contient des tests unitaires pour l'entité `User`.
 * Elle utilise PHPUnit pour vérifier les comportements de base de l'entité `User`.
 */
class UserTest extends TestCase
{
    /**
     * testInitialProperties
     *
     * Teste les propriétés initiales d'un nouvel utilisateur.
     * Vérifie que les propriétés par défaut sont définies correctement.
     */
    public function testInitialProperties(): void
    {
        $user = new User();
        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getPassword());
        $this->assertEmpty($user->getUsername());
        $this->assertEmpty($user->getPosts());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    /**
     * testEmail
     *
     * Teste la gestion de l'email de l'utilisateur.
     * Vérifie que l'email peut être défini et récupéré correctement.
     */
    public function testEmail(): void
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }

    /**
     * testRoles
     *
     * Teste la gestion des rôles de l'utilisateur.
     * Vérifie que les rôles peuvent être définis et récupérés correctement,
     * et que le rôle `ROLE_USER` est toujours présent.
     */
    public function testRoles(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);

        $result = $user->getRoles();
        $this->assertContains('ROLE_ADMIN', $result);
        $this->assertContains(
            'ROLE_USER',
            $result
        ); // ROLE_USER doit toujours être présent
    }

    /**
     * testPassword
     *
     * Teste la gestion du mot de passe de l'utilisateur.
     * Vérifie que le mot de passe peut être défini et récupéré correctement.
     */
    public function testPassword(): void
    {
        $user = new User();
        $password = 'hashed_password';
        $user->setPassword($password);
        $this->assertSame($password, $user->getPassword());
    }

    /**
     * testUsername
     *
     * Teste la gestion du nom d'utilisateur.
     * Vérifie que le nom d'utilisateur peut être défini et récupéré correctement.
     */
    public function testUsername(): void
    {
        $user = new User();
        $username = 'username_test';
        $user->setUsername($username);
        $this->assertSame($username, $user->getUsername());
    }

    /**
     * testPosts
     *
     * Teste la gestion des posts associés à l'utilisateur.
     * Vérifie que les posts peuvent être ajoutés, récupérés et supprimés correctement.
     */
    public function testPosts(): void
    {
        $user = new User();
        $post = $this->createMock(Post::class);

        $post->method('getAuthor')->willReturn($user);

        $user->addPost($post);
        $this->assertCount(1, $user->getPosts());
        $this->assertTrue($user->getPosts()->contains($post));

        $user->removePost($post);
        $this->assertCount(0, $user->getPosts());
    }
}
