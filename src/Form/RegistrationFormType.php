<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This is the RegistrationFormType class.
 *
 * This class extends the base class 'AbstractType'. The purpose of this class
 * is to define the form type that will be used to register a new user.
 * The fields that will be present in the form are defined in the 'buildForm'
 * method.
 *
 * Inside the 'buildForm' method, several fields are added to the form,
 * including:
 * 'email', 'username', 'agreeTerms' checkbox, and 'plainPassword'. The 'email'
 * and 'username' fields are self-explanatory, the 'agreeTerms'
 * checkbox is used for agreeing to terms of service, and the 'plainPassword'
 * field is to get the user's password (with autocomplete feature).
 * Notice that both 'agreeTerms' and 'plainPassword' fields are mapped to false,
 * meaning these are not related to the properties of the data_class.
 *
 * The 'configureOptions' method of this class is used to set default options
 * for the form, in this case setting 'data_class' to 'User::class'.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
