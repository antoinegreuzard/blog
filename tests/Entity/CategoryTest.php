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

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

/**
 * This class is a test suite for the Category Entity.
 *
 * It extends TestCase from PHPUnit framework to gain the unit testing
 * capabilities. The main purpose of this
 * test case is to perform tests on the getName method of the Category Entity.
 *
 * Method : testGetName()
 * Test Summary:
 * It creates a new instance of the Category entity, assigns a name to it and
 * then checks if the assigned
 * name is correctly being returned by the getName() method of the Category
 * entity.
 **/
class CategoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetName()
    {
        $category = new Category();
        $category->setName('Technology');

        $this->assertEquals('Technology', $category->getName());
    }
}
