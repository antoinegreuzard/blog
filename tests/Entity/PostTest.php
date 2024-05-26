<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class PostTest extends TestCase
{
  public function testGetTitle()
  {
    $post = new Post();
    $post->setTitle('My First Post');

    $this->assertEquals('My First Post', $post->getTitle());
  }

  public function testSetGetCategory()
  {
    $category = new Category();
    $category->setName('Technology');

    $post = new Post();
    $post->setCategory($category);

    $this->assertEquals($category, $post->getCategory());
  }

  public function testSetGetAuthor()
  {
    $user = new User();
    $user->setUsername('johndoe');

    $post = new Post();
    $post->setAuthor($user);

    $this->assertEquals($user, $post->getAuthor());
  }
}
