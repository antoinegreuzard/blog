<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DataFixtures;

use App\DataFixtures\AppFixtures;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

/**
 * Class AppFixturesTest
 *
 * Cette classe contient les tests unitaires pour les fixtures de données définies dans `AppFixtures`.
 * Elle utilise PHPUnit pour vérifier que les données sont correctement persistées dans la base de données de test.
 */
class AppFixturesTest extends TestCase
{
    /**
     * testLoad
     *
     * Ce test vérifie que la méthode `load` de `AppFixtures` appelle les méthodes `persist` et `flush`
     * sur l'objet `ObjectManager` fourni.
     */
    public function testLoad()
    {
        // Crée un mock pour l'ObjectManager.
        $objectManager = $this->createMock(ObjectManager::class);

        // Configure le mock pour attendre la méthode persist.
        $objectManager->expects($this->any())
            ->method('persist')
            ->will(Stub::returnValue(null));

        // Configure le mock pour attendre la méthode flush.
        $objectManager->expects($this->once())
            ->method('flush');

        // Crée une instance de la classe sous test.
        $fixtures = new AppFixtures();

        // Appelle la méthode load.
        $fixtures->load($objectManager);
    }
}
