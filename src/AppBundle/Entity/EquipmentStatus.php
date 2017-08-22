<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="equipment_status")
 */
class EquipmentStatus implements \JsonSerializable
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
     * @var string name of the source system
     */
    private $sourceName;

    /**
     * @ORM\Column(type="string")
     *
     * @var string whether this report was done by a traveler or by an employee
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the reported status, either 'OK' or 'KO'
     */
    private $reportedStatus;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool whether or not this status report includes user geolocation
     */
    private $includeUserGeolocation = false;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the end-user geolocation latitude
     */
    private $userGeolocationLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the end-user geolocation longitude
     */
    private $userGeolocationLon = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float accuracy (in meters) of the end-user GPS device
     */
    private $userGpsAccuracy = 0;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="equipmentStatus")
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
     * @ORM\ManyToOne(targetEntity="Equipment", inversedBy="status")
     * @ORM\JoinColumn(name="equipment_id", referencedColumnName="id")
     *
     * @var Equipment
     */
    private $equipment;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return EquipmentStatus
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceName(): string
    {
        return $this->sourceName;
    }

    /**
     * @param string $sourceName
     * @return EquipmentStatus
     */
    public function setSourceName(string $sourceName): EquipmentStatus
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return EquipmentStatus
     */
    public function setType(string $type): EquipmentStatus
    {
        $this->type = $type;
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
     * @return EquipmentStatus
     */
    public function setReportedStatus(string $reportedStatus): EquipmentStatus
    {
        $this->reportedStatus = $reportedStatus;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIncludeUserGeolocation(): bool
    {
        return $this->includeUserGeolocation;
    }

    /**
     * @param boolean $includeUserGeolocation
     * @return EquipmentStatus
     */
    public function setIncludeUserGeolocation(bool $includeUserGeolocation): EquipmentStatus
    {
        $this->includeUserGeolocation = $includeUserGeolocation;
        return $this;
    }

    /**
     * @return float
     */
    public function getUserGeolocationLat(): float
    {
        return $this->userGeolocationLat;
    }

    /**
     * @param float $userGeolocationLat
     * @return EquipmentStatus
     */
    public function setUserGeolocationLat(float $userGeolocationLat): EquipmentStatus
    {
        $this->userGeolocationLat = $userGeolocationLat;
        return $this;
    }

    /**
     * @return float
     */
    public function getUserGeolocationLon(): float
    {
        return $this->userGeolocationLon;
    }

    /**
     * @param float $userGeolocationLon
     * @return EquipmentStatus
     */
    public function setUserGeolocationLon(float $userGeolocationLon): EquipmentStatus
    {
        $this->userGeolocationLon = $userGeolocationLon;
        return $this;
    }

    /**
     * @return float
     */
    public function getUserGpsAccuracy(): float
    {
        return $this->userGpsAccuracy;
    }

    /**
     * @param float $userGpsAccuracy
     * @return EquipmentStatus
     */
    public function setUserGpsAccuracy(float $userGpsAccuracy): EquipmentStatus
    {
        $this->userGpsAccuracy = $userGpsAccuracy;
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
     * @return EquipmentStatus
     */
    public function setUser(User $user): EquipmentStatus
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Equipment
     */
    public function getEquipment(): Equipment
    {
        return $this->equipment;
    }

    /**
     * @param Equipment $equipment
     * @return EquipmentStatus
     */
    public function setEquipment(Equipment $equipment): EquipmentStatus
    {
        $this->equipment = $equipment;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return EquipmentStatus
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
     * @return EquipmentStatus
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
            'equipment' => $this->equipment,
            'type' => $this->type,
            'status' => $this->reportedStatus
        ];
        if ($this->includeUserGeolocation) {
            $return = array_merge(
                $return,
                ['gps' => [
                    'location' => [
                        'lat' => $this->userGeolocationLat,
                        'lon' => $this->userGeolocationLon
                    ],
                    'accuracy' => $this->userGpsAccuracy
                ]]);
        }

        return $return;
    }
}
