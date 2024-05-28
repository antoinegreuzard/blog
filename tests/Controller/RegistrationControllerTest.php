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

    // Vérifiez que la réponse après redirection est réussie (200)
    $this->assertResponseIsSuccessful();

    // Optionnel : Vérifiez que l'utilisateur est redirigé vers la page d'accueil
    $this->assertPageTitleSame('Home');
    $this->assertSelectorTextContains('h1', 'Welcome to the Home Page!');

    // Optionnel : Afficher le contenu pour le débogage si nécessaire
    echo $client->getResponse()->getContent();

    // Vérifiez l'absence de message d'erreur
    $this->assertSelectorNotExists('.alert-danger');
  }
}
