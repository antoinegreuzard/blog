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

    $this->assertResponseRedirects('/');
  }
}
