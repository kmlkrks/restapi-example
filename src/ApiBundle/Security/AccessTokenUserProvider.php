<?php

namespace ApiBundle\Security;

use ApiBundle\Service\AccessTokenService;
use ApiBundle\Service\UserService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class AccessTokenUserProvider
 * @package ApiBundle\Security
 */
class AccessTokenUserProvider implements UserProviderInterface
{
    private $accessTokenService;

    private $userService;

    public function __construct(
        AccessTokenService $accessTokenService,
        UserService $userService
    ) {
        $this->accessTokenService = $accessTokenService;
        $this->userService = $userService;
    }
    /**
     * @param $accessToken
     * @return string
     */
    public function getUsernameForAccessToken($accessToken)
    {
        return $this->accessTokenService->getUser($accessToken);
    }

    /**
     * @param string $username
     * @return UserInterface|void
     */
    public function loadUserByUsername($username)
    {
        return $this->userService->getUserByUsername($username);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface|void
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
} 