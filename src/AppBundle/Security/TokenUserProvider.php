<?php

namespace AppBundle\Security;

use AppBundle\Http\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use AppBundle\Entity\User;

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
            throw new UnauthorizedException(sprintf('No user found with token %s', $token), 'invalid_credentials');
        }

        return $user->getUsername();
    }

    public function loadUserByUsername($username) : User
    {
        return $this->entityManager->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class) : bool
    {
        return User::class === $class;
    }
}
