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
    private $reportedStatus;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the current status, either 'available', 'unavailable', 'coming_soon' or 'disturbed'
     */
    private $currentStatus;

    /**
     * @ORM\Column(type="integer")
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
    public function setId($id)
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
    public function getEquipmentId(): int
    {
        return $this->equipmentId;
    }

    /**
     * @param int $equipmentId
     * @return Location
     */
    public function setEquipmentId(int $equipmentId): Status
    {
        $this->equipmentId = $equipmentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getReportedStatus(): string
    {
        return $this->reportedStatus;
    }

    /**
     * @param string $reportedStatus
     */
    public function setReportedStatus(string $reportedStatus)
    {
        $this->reportedStatus = $reportedStatus;
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
    public function setCurrentStatus(string $currentStatus)
    {
        $this->currentStatus = $currentStatus;
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
            'created_at' => $this->createdAt->format(\DateTime::ISO8601),
            'updated_at' => $this->updatedAt->format(\DateTime::ISO8601),
            'source_name' => $this->sourceName,
            'equipmentId' => $this->equipment,
            'type' => $this->type,
            'status' => $this->reportedStatus
        ];
        if ($this->includeUserGeolocation) {
            $return = array_merge(
                $return,
                ['gps' => [
                    'humanLocation' => [
                        'lat' => $this->userGeolocationLat,
                        'lon' => $this->userGeolocationLon
                    ],
                    'accuracy' => $this->userGpsAccuracy
                ]]);
        }

        return $return;
    }
}
