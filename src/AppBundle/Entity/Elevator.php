<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Elevator
 *
 * @ORM\Table(name="elevator", uniqueConstraints={@ORM\UniqueConstraint(name="elevator_code_source_name_idx", columns={"code", "source_name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElevatorRepository")
 */
class Elevator extends Equipment implements \JsonSerializable
{
    /**
     * @ORM\Column(type="string")
     *
     * @var string id of the station where this equipmentId is located
     */
    private $stationId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string name of the station where this equipmentId is located
     */
    private $stationName;

    /**
     * @ORM\Column(type="string")
     *
     * @var string a human readable string indicating where is this equipmentId
     */
    private $humanLocation;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $direction;

    /**
     * @ORM\OneToMany(targetEntity="StatusPatch", mappedBy="equipmentId")
     *
     * @var ArrayCollection the reported status for this equipmentId
     */
    private $statusPatches;

    /**
     * @ORM\OneToMany(targetEntity="LocationPatch", mappedBy="equipmentId")
     *
     * @var ArrayCollection the reported location for this equipmentId
     */
    private $locationPatches;

    public function __construct()
    {
        $this->statusPatches = new ArrayCollection();
        $this->locationPatches = new ArrayCollection();
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
     * @return Elevator
     */
    public function setStationId(string $stationId): Elevator
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
     * @return Elevator
     */
    public function setStationName(string $stationName): Elevator
    {
        $this->stationName = $stationName;
        return $this;
    }

    /**
     * @return string
     */
    public function getHumanLocation(): string
    {
        return $this->humanLocation;
    }

    /**
     * @param string $humanLocation
     * @return Elevator
     */
    public function setHumanLocation(string $humanLocation): Elevator
    {
        $this->humanLocation = $humanLocation;
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
     * @return Elevator
     */
    public function setDirection(string $direction): Elevator
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStatusPatch(): ArrayCollection
    {
        return $this->statusPatches;
    }

    /**
     * @param ArrayCollection $status
     * @return Elevator
     */
    public function setStatusPatches(ArrayCollection $statusPatches): Elevator
    {
        $this->statusPatches = $statusPatches;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLocationPatches(): ArrayCollection
    {
        return $this->locationPatches;
    }

    /**
     * @param ArrayCollection $locationPatches
     * @return Elevator
     */
    public function setLocationPatches(ArrayCollection $locationPatches): Elevator
    {
        $this->locationPatches = $locationPatches;
        return $this;
    }

    /**
     * Tells whether or not the given equipmentId values are the same as $this.
     * Some properties (such as the creation datetime)
     *
     * @param Equipment $equipment
     * @return bool
     */
    public function equals(self $elevator)
    {
        foreach ($this->getObjectVarsWithoutMetadatas($elevator) as $key => $value) {
            if ($this->$key != $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Updates the current instance from the given equipmentId
     *
     * @param Elevator $elevator
     * @return $this
     */
    public function updateFrom(self $elevator)
    {
        foreach ($this->getObjectVarsWithoutMetadatas($elevator) as $key => $value) {
            $this->$key = $value;
        }
        $this->updatedAt = new \DateTime('now', (new \DateTimeZone('UTC')));
        return $this;
    }

    /**
     * Slightly modified \get_object_vars() to get rid of the property/value we do not want when comparing
     * two instances, so we are able to tell that two instances of this class are equal even if
     * the meta-datas we add (such as: createdAt, status) differ
     *
     * @param Elevator $elevator
     * @return array
     */
    public function getObjectVarsWithoutMetadatas(self $elevator)
    {
        $propsToExclude = ['id', 'createdAt', 'updatedAt', 'statusPatches', 'locationPatches'];

        return array_filter(
            get_object_vars($elevator),
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
            'created_at' => $this->createdAt->format(\DateTime::ISO8601),
            'updated_at' => $this->updatedAt->format(\DateTime::ISO8601)
        ];
    }
}

