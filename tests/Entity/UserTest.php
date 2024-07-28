<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\Post;

class UserTest extends TestCase
{
    public function testInitialProperties()
    {
        $user = new User();
        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getPassword());
        $this->assertEmpty($user->getUsername());
        $this->assertEmpty($user->getPosts());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testEmail()
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testRoles()
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testPassword()
    {
        $user = new User();
        $password = 'hashed_password';
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testUsername()
    {
        $user = new User();
        $username = 'username_test';
        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPosts()
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
