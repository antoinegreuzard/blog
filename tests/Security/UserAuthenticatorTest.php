<?php

/**
 * This file is part of the Blog.
 *
 * (c) Antoine Greuzard <antoine@antoinegreuzard.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Security;

use App\Security\UserAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * Class UserAuthenticatorTest
 *
 * Cette classe contient les tests unitaires pour la classe `UserAuthenticator`.
 * Elle utilise PHPUnit pour vérifier les fonctionnalités liées à l'authentification des utilisateurs.
 */
class UserAuthenticatorTest extends TestCase
{
    /**
     * @var UserAuthenticator $authenticator
     * L'authentificateur utilisateur à tester.
     */
    private UserAuthenticator $authenticator;

    /**
     * @var UrlGeneratorInterface $urlGenerator
     * Générateur d'URL pour les redirections après l'authentification.
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * setUp
     *
     * Méthode exécutée avant chaque test. Elle initialise l'authentificateur et le générateur d'URL.
     */
    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->authenticator = new UserAuthenticator($this->urlGenerator);
    }

    /**
     * testSupports
     *
     * Teste la méthode `supports` pour vérifier si la requête est supportée pour l'authentification.
     * Vérifie que la méthode retourne true pour une requête POST sur la route 'app_login', et false autrement.
     */
    public function testSupports(): void
    {
        $request = new Request([], [], ['_route' => 'app_login']);
        $request->setMethod('POST');

        $this->assertTrue($this->authenticator->supports($request));

        $request->setMethod('GET');
        $this->assertFalse($this->authenticator->supports($request));
    }

    /**
     * testAuthenticate
     *
     * Teste la méthode `authenticate` pour s'assurer qu'un `Passport` est correctement créé avec les badges appropriés.
     * Mocke une session et passe les données de connexion dans la requête.
     */
    public function testAuthenticate(): void
    {
        // Mock de la session
        $session = $this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class);

        // Définir la session sur la requête
        $request = new Request([], [
            'email' => 'user@example.com',
            'password' => 'password123',
            '_csrf_token' => 'csrf_token',
        ]);
        $request->setSession($session);

        // Créez une instance de Passport
        $passport = $this->authenticator->authenticate($request);

        // Vérifiez que le Passport est bien créé et contient les informations correctes
        $this->assertInstanceOf(Passport::class, $passport);
        $this->assertEquals('user@example.com', $passport->getBadge(UserBadge::class)->getUserIdentifier());
        $this->assertEquals('password123', $passport->getBadge(PasswordCredentials::class)->getPassword());
        $this->assertInstanceOf(CsrfTokenBadge::class, $passport->getBadge(CsrfTokenBadge::class));
        $this->assertInstanceOf(RememberMeBadge::class, $passport->getBadge(RememberMeBadge::class));
    }

    /**
     * testOnAuthenticationSuccess
     *
     * Teste la méthode `onAuthenticationSuccess` pour s'assurer qu'une redirection appropriée est effectuée après une authentification réussie.
     * Mocke la session et vérifie la redirection vers la page d'accueil.
     */
    public function testOnAuthenticationSuccess(): void
    {
        $request = new Request();
        $request->setSession($this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class));
        $token = $this->createMock(TokenInterface::class);

        $this->urlGenerator->expects($this->once())
            ->method('generate')
            ->with('home')
            ->willReturn('/home');

        $response = $this->authenticator->onAuthenticationSuccess($request, $token, 'main');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/home', $response->getTargetUrl());
    }

    /**
     * testGetLoginUrl
     *
     * Teste la méthode `getLoginUrl` pour s'assurer que l'URL de la page de connexion est générée correctement.
     * Vérifie que l'URL générée est '/login'.
     */
    public function testGetLoginUrl(): void
    {
        $request = new Request();

        $this->urlGenerator->expects($this->once())
            ->method('generate')
            ->with(UserAuthenticator::LOGIN_ROUTE)
            ->willReturn('/login');

        $loginUrl = $this->authenticator->getLoginUrl($request);

        $this->assertEquals('/login', $loginUrl);
    }
}
