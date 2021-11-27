<?php 

// src/Security/LoginFormAuthenticator.php
namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'cp_app_login';

    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
		
		$user = $token->getUser();
		
		if(!($user instanceof User))
			$user = null;
	
		if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        
        return new RedirectResponse($this->urlGenerator->generate('cp_dashboard'));
    }
	
	public function authenticate(Request $request): PassportInterface
	{
		$email = $request->request->get('email');
		$password = $request->request->get('password');
		$csrfToken = $request->request->get('_csrf_token');
		
		return new Passport(
			new UserBadge($email),
			new PasswordCredentials($password),
			[
				new CsrfTokenBadge('authenticate', $csrfToken)
			]
		);
	}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
	{
		
		if ($request->hasSession()) {
			$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
		}
		
		$url = $this->urlGenerator->generate(self::LOGIN_ROUTE);
		
		return new RedirectResponse($url);
	}
	
	public function start(Request $request, AuthenticationException $authException = null) : RedirectResponse
	{
		if ($request->hasSession()) {
			$request->getSession()->set(Security::AUTHENTICATION_ERROR, $authException);
		}
		
		$url = $this->urlGenerator->generate(self::LOGIN_ROUTE);
		
		return new RedirectResponse($url);
	}
}
