<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * StopPoint
 *
 * @ORM\Table(name="stop_point")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StopPointRepository")
 */
class StopPoint extends Equipment implements \JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="route_id", type="string", length=255)
     */
    private $routeId;

    /**
     * @var string
     *
     * @ORM\Column(name="route_name", type="string", length=255)
     */
    private $routeName;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="equipmentId")
     *
     * @var ArrayCollection the reported location for this equipmentId
     */
    private $locations;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->locations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getLocations(): ArrayCollection
    {
        return $this->locations;
    }

    /**
     * @param ArrayCollection $locations
     * @return Elevator
     */
    public function setLocations(ArrayCollection $locations): StopPoint
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return StopPoint
     */
    public function setName(string $name) : StopPoint
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set routeId
     *
     * @param string $routeId
     *
     * @return StopPoint
     */
    public function setRouteId(string $routeId) : StopPoint
    {
        $this->routeId = $routeId;

        return $this;
    }

    /**
     * Get routeId
     *
     * @return string
     */
    public function getRouteId() : string
    {
        return $this->routeId;
    }

    /**
     * Set routeName
     *
     * @param string $routeName
     *
     * @return StopPoint
     */
    public function setRouteName(string $routeName) : StopPoint
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * Get routeName
     *
     * @return string
     */
    public function getRouteName() : string
    {
        return $this->routeName;
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

