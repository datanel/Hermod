<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * StopPoint
 *
 * @ORM\Table(name="stop_point", uniqueConstraints={@ORM\UniqueConstraint(name="stop_point_code_source_name_idx", columns={"code", "source_name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StopPointRepository")
 */
class StopPoint extends Equipment implements \JsonSerializable
{
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
     * @ORM\OneToMany(targetEntity="LocationPatch", mappedBy="equipmentId")
     *
     * @var ArrayCollection the reported location for this equipmentId
     */
    private $locationPatches;

    public function __construct()
    {
        $this->locationPatches = new ArrayCollection();
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
    public function setLocationPatches(ArrayCollection $locationPatches): StopPoint
    {
        $this->locationPatches = $locationPatches;
        return $this;
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

