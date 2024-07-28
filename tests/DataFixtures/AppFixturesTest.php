<?php

namespace App\Tests\DataFixtures;

use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\AppFixtures;

class AppFixturesTest extends TestCase
{
    public function testLoad()
    {
        // Create a mock for the ObjectManager.
        $objectManager = $this->createMock(ObjectManager::class);

        // Configure the mock to expect the persist method.
        $objectManager->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null));

        // Configure the mock to expect the flush method.
        $objectManager->expects($this->once())
            ->method('flush');

        // Create an instance of the class under test.
        $fixtures = new AppFixtures();

        // Call the load method.
        $fixtures->load($objectManager);
    }
}
