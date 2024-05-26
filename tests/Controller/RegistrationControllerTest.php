<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
  public function testRegisterPageLoads()
  {
    $client = static::createClient();
    $client->request('GET', '/register');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Register');
  }

  public function testRegisterFormSubmission()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/register');

    $form = $crawler->selectButton('Register')->form([
      'registration_form[email]' => 'test@example.com',
      'registration_form[username]' => 'testuser',
      'registration_form[plainPassword]' => 'password123',
      'registration_form[agreeTerms]' => true,
    ]);

    $client->submit($form);

    $this->assertResponseRedirects('/');

    $client->followRedirect();
    $this->assertSelectorTextContains('h1', 'Welcome to the Home Page!');
  }
}
