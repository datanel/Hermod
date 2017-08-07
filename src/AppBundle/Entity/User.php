<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string The user name
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     *
     * @var string The user token
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="LocationPatch", mappedBy="user")
     *
     * @var ArrayCollection User patches
     */
    private $patches;

    /**
     * @ORM\OneToMany(targetEntity="EquipmentStatus", mappedBy="user")
     *
     * @var ArrayCollection User status reports
     */
    private $equipmentStatus;

    public function __construct($username, $token)
    {
        $this->username = $username;
        $this->token = $token;
        $this->patches = new ArrayCollection();
        $this->equipmentStatus = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoles() : array
    {
        return ['ROLE_USER'];
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

    public function getPatches()
    {
        return $this->patches;
    }

    public function getStatusReports()
    {
        return $this->equipmentStatus;
    }

    public function __toString()
    {
        return $this->username;
    }
}
