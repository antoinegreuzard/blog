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

    // Check if the response contains error message about existing email
    $this->assertSelectorTextContains('.alert-danger', 'This email is already registered.');
  }
}
    