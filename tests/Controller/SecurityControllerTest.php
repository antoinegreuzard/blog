<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Login');
    }

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
            $testUser->setPassword(password_hash('password123', PASSWORD_BCRYPT));

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
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }

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
