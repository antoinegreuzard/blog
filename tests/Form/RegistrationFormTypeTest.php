<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Form;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * The `RegistrationFormTypeTest` class extends the `TypeTestCase` class,
 * provided by Symfony's Form Component.
 * It's used to test the `RegistrationFormType`, that handles User r
 * egistration forms.
 *
 * @category Tests
 *
 * The `testSubmitValidData()` method tests whether the form submission works
 * with valid data.
 * It first declares an associative array `$formData` with keys and values
 * mimicking a valid user registration input.
 * Then it creates an empty `User` object instance `$model` and a form `$form`
 * tied to this model.
 * An expected user `$expected` is created and filled with expected data.
 * The form is submitted with the test data, and it's checked whether the form
 * is synchronized and whether the submitted data matches the expected data.
 */
class RegistrationFormTypeTest extends TypeTestCase
{
    /**
     * @return void
     */
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
