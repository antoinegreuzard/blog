<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
  private KernelBrowser $client;
  private ?object $entityManager;

  protected function setUp(): void
  {
    $this->client = static::createClient();
    $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
  }

  protected function tearDown(): void
  {
    parent::tearDown();

    if ($this->entityManager) {
      $this->entityManager->close();
      $this->entityManager = null;
    }
  }

  public function testRegisterPageLoadsCorrectly()
  {
    $crawler = $this->client->request('GET', '/register');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Register');
    $this->assertCount(1, $crawler->filter('form'));
  }

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

    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
    $this->assertNotNull($user);
    $this->assertSame('testuser', $user->getUsername());
  }

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
    $this->assertSelectorTextContains('.alert-danger', 'This email is already registered.');
  }
}
