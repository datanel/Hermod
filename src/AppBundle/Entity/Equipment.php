<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="equipment")
 */
class Equipment implements \JsonSerializable
{
    const TYPE_ELEVATOR = 'elevator';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string code
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     *
     * @var string type of this equipment
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     *
     * @var string id of the station where this equipment is located
     */
    private $stationId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string name of the station where this equipment is located
     */
    private $stationName;

    /**
     * @ORM\Column(type="string")
     *
     * @var string a human readable string indicating where is this equipment
     */
    private $location;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $direction;

    /**
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime The date and time at which this equipment was created in this system
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="EquipmentStatus", mappedBy="equipment")
     *
     * @var ArrayCollection the reported status for this equipment
     */
    private $status;

    public function __construct($id)
    {
        $this->id = $id;
        $this->status = new ArrayCollection();
        $this->createdAt = new \DateTime;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Equipment
     */
    public function setCode(string $code): Equipment
    {
        $this->code = $code;
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
     * @return Equipment
     */
    public function setType(string $type): Equipment
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getStationId(): string
    {
        return $this->stationId;
    }

    /**
     * @param string $stationId
     * @return Equipment
     */
    public function setStationId(string $stationId): Equipment
    {
        $this->stationId = $stationId;
        return $this;
    }

    /**
     * @return string
     */
    public function getStationName(): string
    {
        return $this->stationName;
    }

    /**
     * @param string $stationName
     * @return Equipment
     */
    public function setStationName(string $stationName): Equipment
    {
        $this->stationName = $stationName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Equipment
     */
    public function setLocation(string $location): Equipment
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     * @return Equipment
     */
    public function setDirection(string $direction): Equipment
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Equipment
     */
    public function setCreatedAt(\DateTime $createdAt): Equipment
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStatus(): ArrayCollection
    {
        return $this->status;
    }

    /**
     * @return array the list of available equipment types
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_ELEVATOR
        ];
    }

    /**
     * @param ArrayCollection $status
     * @return Equipment
     */
    public function setStatus(ArrayCollection $status): Equipment
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Tells whether or not the given equipment values are the same as $this.
     * Some properties (such as the creation datetime)
     *
     * @param Equipment $equipment
     * @return bool
     */
    public function equals(self $equipment)
    {
        foreach ($this->getObjectVarsWithoutMetadatas($equipment) as $key => $value) {
            if ($this->$key != $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Updates the current instance from the given equipment
     *
     * @param Equipment $equipment
     * @return $this
     */
    public function updateFrom(self $equipment)
    {
        foreach ($this->getObjectVarsWithoutMetadatas($equipment) as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * Slightly modified \get_object_vars() to get rid of the property/value we do not want when comparing
     * two instances, so we are able to tell that two instances of this class are equal even if
     * the meta-datas we add (such as: createdAt, status) differ
     *
     * @param Equipment $equipment
     * @return array
     */
    public function getObjectVarsWithoutMetadatas(self $equipment)
    {
        $propsToExclude = ['createdAt', 'status'];
        return array_filter(
            get_object_vars($equipment),
            function ($propName) use ($propsToExclude) {
                return !in_array($propName, $propsToExclude);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'station' => [
                'id' => $this->stationId,
                'name' => $this->stationName
            ],
            'location' => $this->location,
            'direction' => $this->direction
        ];
    }
}
