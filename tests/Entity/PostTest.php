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
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * PostTest Class.
 *
 * This class extends the TestCase class from the PHPUnit Framework. It contains
 * methods for testing the functionality
 * of the Post class's title, category, and author operations. These tests
 * validate setting and getting the title,
 * category, and author for a Post object.
 *
 * testGetTitle:
 * Method for testing the getTitle operation of the Post class. It creates a
 * new Post object and sets its title to
 * 'My First Post'. Then, it checks if the title fetched matches what was set.
 *
 * testSetGetCategory:
 * Method for testing the setCategory and getCategory operations of the Post
 * class. It creates a new Category object, sets its
 * name to 'Technology', and then assigns this category to a new Post object.
 * It then checks if the category fetched matches
 * with the category set for the post.
 *
 * testSetGetAuthor:
 * This method tests the setAuthor and getAuthor operations of the Post class.
 * It creates a new User object, sets its
 * username to 'johndoe', and then assigns this user as the author of a new Post
 * object. Checks if the author fetched
 * matches the author set for the post.
 */
class PostTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTitle()
    {
        $post = new Post();
        $post->setTitle('My First Post');

        $this->assertEquals('My First Post', $post->getTitle());
    }

    /**
     * @return void
     */
    public function testSetGetCategory()
    {
        $category = new Category();
        $category->setName('Technology');

        $post = new Post();
        $post->setCategory($category);

        $this->assertEquals($category, $post->getCategory());
    }

    /**
     * @return void
     */
    public function testSetGetAuthor()
    {
        $user = new User();
        $user->setUsername('johndoe');

        $post = new Post();
        $post->setAuthor($user);

        $this->assertEquals($user, $post->getAuthor());
    }
}
