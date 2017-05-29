<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class TokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    public function createToken(Request $request, $providerKey) : PreAuthenticatedToken
    {
        $apiKey = $request->headers->get('Authorization');

        if (!$apiKey) {
            throw new BadCredentialsException(
                'You must provide a token under the Authorization header to access this resource'
            );
        }

        return new PreAuthenticatedToken('anon.', $apiKey, $providerKey);
    }

    public function supportsToken(TokenInterface $token, $providerKey) : boolean
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(
        TokenInterface $token,
        UserProviderInterface $userProvider,
        $providerKey
    ) : PreAuthenticatedToken {
        if (!$userProvider instanceof TokenUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of %s (%s was given).',
                    TokenUserProvider::class,
                    get_class($userProvider)
                )
            );
        }

        $token = $token->getCredentials();
        $username = $userProvider->getUsernameForToken($token);

        if (!$username) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('Token %s does not exist.', $token)
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $token,
            $providerKey,
            $user->getRoles()
        );
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ) : JsonResponse {
        return new JsonResponse(
            ['error' => 'invalid_credentials', 'message' => $exception->getMessage()],
            401
        );
    }
}
