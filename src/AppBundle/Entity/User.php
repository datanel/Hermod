<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function __toString()
    {
        return $this->username;
    }
}
