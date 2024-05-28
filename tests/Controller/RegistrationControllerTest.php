<?php

namespace App\Tests\Controller;

use Exception;
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

  /**
   * @throws Exception
   */
  public function testRegisterFormSubmission()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/register');

    // Ajoutez une assertion pour vérifier que le formulaire est présent
    $this->assertSelectorExists('form[name="registration_form"]');

    $form = $crawler->selectButton('Register')->form([
      'registration_form[email]' => 'test@example.com',
      'registration_form[username]' => 'testuser',
      'registration_form[plainPassword]' => 'password123',
      'registration_form[agreeTerms]' => true,
    ]);

    $client->submit($form);

    // Suivre la redirection
    $client->followRedirect();

    // Vérifiez que la soumission du formulaire est réussie et que l'utilisateur est redirigé
    try {
      $this->assertResponseIsSuccessful();
      // Vérifiez si la réponse contient un message de succès ou redirige vers la page de connexion
      $this->assertSelectorExists('.alert-danger');
    } catch (Exception $e) {
      echo $client->getResponse()->getContent();
      throw $e;
    }
  }
}
