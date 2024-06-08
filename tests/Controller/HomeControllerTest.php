<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * HomeControllerTest Class.
 *
 * This class extends the WebTestCase that is part of the Symfony Framework.
 * It's used for functional testing where each test must interact with the real
 * Symfony application.
 *
 * PHPUnit will automatically load this class during the testing phase as
 * it's a test class (suffix "Test").
 *
 * @extends WebTestCase Symfony's basic functional test class.
 */
class HomeControllerTest extends WebTestCase
{
    /**
     * @return void
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome to the Home Page!');
    }
}
