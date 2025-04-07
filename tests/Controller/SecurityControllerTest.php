<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest extends the WebTestCase to create a series of
 * tests related to application's security routes.
 * This class has following five methods:
 *
 * - The `testLoginPageLoads`: This method ensures that the login page is being
 * loaded successfully.
 * - The `testLoginFormSubmission`: It tests the submission of the login form
 * with valid user credentials.
 * - The `testLoginFormSubmissionWithInvalidCredentials`: It tests the
 * submission of login form with invalid credentials,
 * ensuring only valid users can process through login page.
 * - The `testLogout`: It tests the logout functionality, ensuring the logout
 * action leads to the correct endpoint.
 *
 * Each test case follows a similar pattern where it starts by creating a client
 * to make HTTP requests to application's routes.
 * After a request has been sent, it uses built-in assert methods provided by
 * WebTestCase to confirm that the received response
 * meets the expected criteria.
 *
 * The `SecurityControllerTest` class ensures the integrity of the application's
 * login system by simulating the web interaction a
 * user would have when entering, submitting or failing to meet the criteria of
 * the login form, and finally logging out.
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * @return void
     */
    public function testLoginPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Login');
    }

    /**
     * @return void
     */
    public function testLoginFormSubmission()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);

        $userRepository = $container->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('test@example.com');

        if (!$testUser) {
            $testUser = new User();
            $testUser->setEmail('test@example.com');
            $testUser->setUsername('test');
            $testUser->setPassword(
                password_hash('password123', PASSWORD_BCRYPT)
            );

            $entityManager->persist($testUser);
            $entityManager->flush();
        }

        $crawler = $client->request('GET', '/login');

        $csrfToken = $crawler->filter('input[name=_csrf_token]')->attr('value');

        $client->request('POST', '/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            '_csrf_token' => $csrfToken,
        ]);

        $client->followRedirect();

        $content = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();

        $this->assertEquals('/', $client->getRequest()->getPathInfo());
    }

    /**
     * @return void
     */
    public function testLoginFormSubmissionWithInvalidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $client->submit($form);

        // Follow the redirect to see the error message
        $client->followRedirect();
        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains(
            '.alert-danger',
            'Invalid credentials.'
        );
    }

    /**
     * @return void
     */
    public function testLogout()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');

        // Follow the redirect to see the final destination
        $client->followRedirect();

        // Check if the final destination is the homepage
        $this->assertResponseIsSuccessful();
        $this->assertEquals('/', $client->getRequest()->getPathInfo());
    }
}
