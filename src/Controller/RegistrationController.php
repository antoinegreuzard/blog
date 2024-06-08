<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception as ExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * RegistrationController Class
 *
 * This class extends from the Symfony abstract controller, handling user
 * registration requests.
 * It defines a single public method, 'register'. It has an associated route
 * '/register' and serves to manage the
 * user registration process.
 *
 * Following 'register' method parameters:
 *   1. Request $request: The current HTTP request object.
 *   2. UserPasswordHasherInterface $userPasswordHasher: An instance of the
 * password hasher interface.
 *   3. EntityManagerInterface $entityManager: The Entity Manager allows
 * interaction with ORM.
 *   4. UserAuthenticatorInterface $userAuthenticator: Interface to authenticate
 * user.
 *   5. UserAuthenticator $authenticator: The user authenticator.
 *
 * In the 'register' method, user data from the registration form is collected
 * and validated. If there are no validation
 * errors, the user account is created, and user is flushed to the database.
 * If the registration is successful, the
 * user is also authenticated and the request is returned. In case of any errors,
 * they are logged and an error message is returned.
 *
 * The 'register' method returns a Response object, representing an HTTP
 * response. The response typically is
 * a twig render, representing the form, along with any input validation error
 * messages.
 *
 * For handling exceptions, it uses try-catch blocks to handle specific
 * exceptions that may be thrown during the
 * registration process. For example, UniqueConstraintViolationException is
 * thrown when there is an attempt to
 * register with an already registered email.
 */
class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param UserAuthenticator $authenticator
     *
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $email = $form->get('email')->getData();
            $username = $form->get('username')->getData();
            $plainPassword = $form->get('plainPassword')->getData();
            $agreeTerms = $form->get('agreeTerms')->getData();

            $errors = [];

            if (!$email) {
                $errors[] = 'Email should not be blank.';
            }

            if (!$username) {
                $errors[] = 'Username should not be blank.';
            }

            if (!$plainPassword) {
                $errors[] = 'Password should not be blank.';
            } elseif (strlen($plainPassword) < 6) {
                $errors[] = 'Password should be at least 6 characters.';
            }

            if (!$agreeTerms) {
                $errors[] = 'You should agree to our terms.';
            }

            if (empty($errors)) {
                try {
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $plainPassword
                        )
                    );

                    $entityManager->persist($user);
                    $entityManager->flush();

                    return $userAuthenticator->authenticateUser(
                        $user,
                        $authenticator,
                        $request
                    );
                } catch (UniqueConstraintViolationException $e) {
                    $errors[] = 'This email is already registered.';
                } catch (ExceptionAlias $e) {
                    $errors[] = 'An unexpected error occurred. Please try again later.';
                    error_log($e->getMessage());
                }
            }

            foreach ($errors as $error) {
                $this->addFlash('error', $error);
            }
        }

        return $this->render('registration/register.html.twig', ['registrationForm' => $form->createView()]);
    }
}
