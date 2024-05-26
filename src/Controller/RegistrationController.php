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


    // d($form->get('plainPassword') && $form->get('username') && $form->get('email') && $form->get('agreeTerms'));

    if ($form->isSubmitted() && $form->get('plainPassword') && $form->get('username') && $form->get('email') && $form->get('agreeTerms')) {
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );


      $entityManager->persist($user);
      $entityManager->flush();

      return $this->redirectToRoute('home');
    } else {
      foreach ($form->getErrors(true) as $error) {
        $this->addFlash('error', $error->getMessage());
      }
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }
}
