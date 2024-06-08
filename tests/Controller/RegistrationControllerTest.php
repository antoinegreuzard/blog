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

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RegistrationControllerTest.
 *
 * This class extends WebTestCase, which provides the functionality for testing
 * HTTP paper_testing_library. It contains tests to validate the behavior of
 * the registration feature in a web application.
 *
 * Properties:
 *
 * @private  KernelBrowser $client - A client instance used to make requests.
 * @private  ?object $entityManager - An entity manager instance used to
 * interact with the database.
 *
 * Methods:
 *
 * @protected setUp(): void - This method sets up the test environment,
 * called before execution of each test method.
 * @protected tearDown(): void - This method tears down the test environment,
 * called after execution of each test method.
 *
 * @public testRegisterPageLoadsCorrectly() - This test ensures that the
 * registration page loads successfully.
 * @public testFormSubmissionWithValidData() - This test ensures that the
 * registration form behaves as expected when submitted with valid data.
 * @public testDuplicateEmailSubmission() - This test ensures that the
 * registration form behaves as expected when an attempt is made to register
 * an email that already exists.
 */
class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ?object $entityManager;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get(
            'doctrine.orm.entity_manager'
        );
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }

    /**
     * @return void
     */
    public function testRegisterPageLoadsCorrectly()
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
        $this->assertCount(1, $crawler->filter('form'));
    }

    /**
     * @return void
     */
    public function testFormSubmissionWithValidData()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[username]' => 'testuser',
            'registration_form[plainPassword]' => 'password123',
            'registration_form[agreeTerms]' => true,
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(
            ['email' => 'test@example.com']
        );
        $this->assertNotNull($user);
        $this->assertSame('testuser', $user->getUsername());
    }

    /**
     * @return void
     */
    public function testDuplicateEmailSubmission()
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form([
            'registration_form[email]' => 'duplicate@example.com',
            'registration_form[username]' => 'user1',
            'registration_form[plainPassword]' => 'password123',
            'registration_form[agreeTerms]' => true,
        ]);

        // Submit the form twice to simulate duplicate email submission
        $this->client->submit($form);
        $this->client->request('GET', '/register');
        $this->client->submit($form);

        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains(
            '.alert-danger',
            'This email is already registered.'
        );
    }
}
