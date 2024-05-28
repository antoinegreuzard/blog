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

    $this->assertSelectorExists('form[name="registration_form"]');

    $form = $crawler->selectButton('Register')->form([
      'registration_form[email]' => 'test@example.com',
      'registration_form[username]' => 'testuser',
      'registration_form[plainPassword]' => 'password123',
      'registration_form[agreeTerms]' => true,
    ]);

    $client->submit($form);

    $this->assertResponseIsSuccessful();

    echo $client->getResponse()->getStatusCode();
    echo $client->getResponse()->getContent();

    $this->assertSelectorTextContains('.alert-danger', 'This email is already registered.');
  }
}
