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

    $user = new User();
    $form = $this->factory->create(RegistrationFormType::class, $user);

    $expected = new User();
    $expected->setEmail('test@example.com');
    $expected->setUsername('testuser');

    $form->submit($formData);
    $this->assertTrue($form->isSynchronized());
    $this->assertEquals($expected->getEmail(), $user->getEmail());
    $this->assertEquals($expected->getUsername(), $user->getUsername());
  }
}
