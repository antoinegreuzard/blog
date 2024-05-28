<?php

namespace App\Tests\Controller;

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
    $crawler = $client->request('GET', '/login');

    $form = $crawler->selectButton('Sign in')->form([
      'email' => 'test@example.com',
      'password' => 'password123',
    ]);

    $client->submit($form);

    // Follow the redirect to see the final destination
    $client->followRedirect();

    // Check if the final destination is the login page, indicating a failed login attempt
    $this->assertResponseIsSuccessful();
    $this->assertEquals('/login', $client->getRequest()->getPathInfo());
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
    