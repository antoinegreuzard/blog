<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
  #[Route('/register', name: 'app_register')]
  public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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
        $user->setPassword(
          $userPasswordHasher->hashPassword(
            $user,
            $plainPassword
          )
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('home');
      } else {
        foreach ($errors as $error) {
          $this->addFlash('error', $error);
        }
      }
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }
}    