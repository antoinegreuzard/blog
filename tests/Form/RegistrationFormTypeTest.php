<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationFormTypeTest extends TypeTestCase
{
  public function testSubmitValidData()
  {
    $formData = [
      'email' => 'test@example.com',
      'username' => 'testuser',
      'plainPassword' => 'password123',
      'agreeTerms' => true,
    ];

    $model = new User();
    $form = $this->factory->create(RegistrationFormType::class, $model);

    $expected = new User();
    $expected->setEmail('test@example.com');
    $expected->setUsername('testuser');

    // Soumettre les données du formulaire
    $form->submit($formData);

    // Vérifiez que le formulaire est synchronisé
    $this->assertTrue($form->isSynchronized());

    // Vérifiez que les données attendues correspondent aux données réelles
    $this->assertEquals($expected->getEmail(), $model->getEmail());
    $this->assertEquals($expected->getUsername(), $model->getUsername());
  }
}
