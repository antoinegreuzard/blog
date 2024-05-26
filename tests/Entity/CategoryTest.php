<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
  public function testGetName()
  {
    $category = new Category();
    $category->setName('Technology');

    $this->assertEquals('Technology', $category->getName());
  }
}
