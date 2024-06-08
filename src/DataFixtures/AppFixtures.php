<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures
 *
 * This class extends the Fixture class from the Doctrine\Bundle\FixturesBundle
 * package and it is part of View for Data Fixtures in the application, designed
 * for managing
 * fixture loading - a very common task during the development process
 * when you often need to work with dummy data.
 *
 * It has a main function 'load' which is responsible for handling Doctrine’s
 * interaction with your database system to store data.
 * This 'load' function takes an ObjectManager as a parameter and
 * don't return a value.
 *
 * @extends \Doctrine\Bundle\FixturesBundle\Fixture
 */
class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
