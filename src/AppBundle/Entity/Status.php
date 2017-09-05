<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="status", indexes={@ORM\Index(name="status_equipment_id_idx", columns={"equipment_id"})})
 */
class Status implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the reported status, either 'available', 'unavailable', 'coming_soon' or 'disturbed'
     */
    private $patchedStatus;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the current status, either 'available', 'unavailable', 'coming_soon' or 'disturbed'
     */
    private $currentStatus;

    /**
     * @ORM\Column(type="guid")
     *
     * @var EquipmentId
     */
    private $equipmentId;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="statusPatches")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User The user submitting the report
     */
    private $user;

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

    /**
     * @return mixed
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Status
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Status
     */
    public function setUser(User $user): Status
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getEquipmentId(): string
    {
        return $this->equipmentId;
    }

    /**
     * @param int $equipmentId
     * @return Location
     */
    public function setEquipmentId(string $equipmentId): Status
    {
        $this->equipmentId = $equipmentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPatchedStatus(): string
    {
        return $this->patchedStatus;
    }

    /**
     * @param string $patchedStatus
     */
    public function setPatchedStatus(string $patchedStatus)
    {
        $this->patchedStatus = $patchedStatus;
    }

    /**
     * @return string
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    /**
     * @param string $currentStatus
     */
    public function setCurrentStatus(string $currentStatus) : Status
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Status
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
     * @return Status
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

    public function jsonSerialize() : array
    {
        $return = [
            'created_at' => $this->getCreatedAt()->format(\DateTime::ISO8601),
            'updated_at' => $this->getUpdatedAt()->format(\DateTime::ISO8601),
            'equipment_d' => $this->getEquipmentId(),
            'current_status' => $this->getCurrentStatus(),
            'patched_status' => $this->getPatchedStatus()
        ];

        return $return;
    }
}
