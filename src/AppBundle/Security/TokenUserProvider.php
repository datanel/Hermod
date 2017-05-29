<?php

namespace AppBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenUserProvider implements UserProviderInterface
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUsernameForToken($token) : string
    {
        $user = $this->entityManager->getRepository('AppBundle:User')->findOneBy(['token' => $token]);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user found with token %s', $token));
        }

        return $user->getUsername();
    }

    public function loadUserByUsername($username) : User
    {
        return new User(
            $username,
            null,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            ['ROLE_API']
        );
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class) : boolean
    {
        return User::class === $class;
    }
}
