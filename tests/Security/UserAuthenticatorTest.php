<?php

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

class UserAuthenticatorTest extends TestCase
{
    private UserAuthenticator $authenticator;
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->authenticator = new UserAuthenticator($this->urlGenerator);
    }

    public function testSupports(): void
    {
        $request = new Request([], [], ['_route' => 'app_login']);
        $request->setMethod('POST');

        $this->assertTrue($this->authenticator->supports($request));

        $request->setMethod('GET');
        $this->assertFalse($this->authenticator->supports($request));
    }

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
