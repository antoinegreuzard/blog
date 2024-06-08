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

use LogicException as LogicExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 *
 * A Symfony-based controller handling security-related actions such as login
 * and logout. It extends Symfony's AbstractController
 * and uses Symfony's routing and security components for authentication
 * purposes.
 *
 *
 * METHODS:
 *
 * #1 login(AuthenticationUtils $authenticationUtils):  Handles the login
 * process for a user. If the user is already logged in, they
 * are redirected to the homepage. If there are authentication issues, an e
 * rror message is retrieved and displayed to the user along
 * with the last entered username. The function finally returns a rendered
 * login page.
 *
 * #2 logout(): Handles the logout process. This method is intentionally left
 * blank as the logout is handled by the Symfony
 * Security component via the 'logout' key on the firewall configuration.
 */
class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicExceptionAlias(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
