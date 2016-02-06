<?php

namespace ApiBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Class AccessTokenAuthenticator
 * @package ApiBundle\Security
 */
class AccessTokenAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException
     */
    public function authenticateToken(
        TokenInterface $token,
        UserProviderInterface $userProvider,
        $providerKey
    ) {
        if (!$userProvider instanceof AccessTokenUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of AccessTokenUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $accessToken = $token->getCredentials();
        $username = $userProvider->getUsernameForAccessToken($accessToken);

        if (!$username) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('Access token "%s" does not exist.', $accessToken)
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user,
            $accessToken,
            $providerKey,
            $user->getRoles()
        );
    }

    public function createToken(Request $request, $providerKey)
    {
        $accessToken = $request->headers->get('x-access-token');

        if (!$accessToken) {
            throw new BadCredentialsException('No access token found');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $accessToken,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}