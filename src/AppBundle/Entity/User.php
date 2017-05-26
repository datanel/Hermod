<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="api_user")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string The user name
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     *
     * @var string The user token
     */
    protected $token;

    public function __construct($username, $token)
    {
        $this->username = $username;
        $this->token = $token;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoles() : array
    {
        return ['ROLE_API'];
    }

    public function getToken() : string
    {
        return $this->token;
    }

    public function setToken($token) : User
    {
        $this->token = $token;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username) : User
    {
        $this->username = $username;
        return $this;
    }
}