<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="location_patch")
 */
class LocationPatch implements \JsonSerializable
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
     * @var string The name of the source system
     */
    private $sourceName;

    /**
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime The date and time on which this patch has been created
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean the reporting mode used, can be either
     *  - false if that patch was created by simply giving it a location
     *  - true if the end-user provided his GPS geolocation to the API to
     *    indicate the new location of the stop point
     */
    private $usingUserGeolocation;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id of the stop point we want to patch
     */
    private $stopPointId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id of the stop point we want to patch
     */
    private $stopPointName;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the stop point current latitude
     */
    private $stopPointCurrentLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the stop point current longitude
     */
    private $stopPointCurrentLon = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the suggested latitude
     */
    private $stopPointPatchedLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the suggested longitude
     */
    private $stopPointPatchedLon = 0;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id of the route passing by the stop point to patch
     */
    private $routeId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id name the route passing by the stop point to patch
     */
    private $routeName;

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
     * Many Patches have One User.
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="patches")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User The user submitting the patch
     */
    private $user;

    public function __construct()
    {
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
     * @param mixed $id
     * @return LocationPatch
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
     * @return LocationPatch
     */
    public function setSourceName(string $sourceName): LocationPatch
    {
        $this->sourceName = $sourceName;
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
     * @return LocationPatch
     */
    public function setCreatedAt(\DateTime $createdAt): LocationPatch
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isUsingUserGeolocation(): bool
    {
        return $this->usingUserGeolocation;
    }

    /**
     * @param boolean $usingUserGeolocation
     * @return LocationPatch
     */
    public function setUsingUserGeolocation(bool $usingUserGeolocation): LocationPatch
    {
        $this->usingUserGeolocation = $usingUserGeolocation;
        return $this;
    }

    /**
     * @return string
     */
    public function getStopPointId(): string
    {
        return $this->stopPointId;
    }

    /**
     * @param string $stopPointId
     * @return LocationPatch
     */
    public function setStopPointId(string $stopPointId): LocationPatch
    {
        $this->stopPointId = $stopPointId;
        return $this;
    }

    /**
     * @return string
     */
    public function getStopPointName(): string
    {
        return $this->stopPointName;
    }

    /**
     * @param string $stopPointName
     * @return LocationPatch
     */
    public function setStopPointName(string $stopPointName): LocationPatch
    {
        $this->stopPointName = $stopPointName;
        return $this;
    }

    /**
     * @return float
     */
    public function getStopPointCurrentLat(): float
    {
        return $this->stopPointCurrentLat;
    }

    /**
     * @param float $stopPointCurrentLat
     * @return LocationPatch
     */
    public function setStopPointCurrentLat(float $stopPointCurrentLat): LocationPatch
    {
        $this->stopPointCurrentLat = $stopPointCurrentLat;
        return $this;
    }

    /**
     * @return float
     */
    public function getStopPointCurrentLon(): float
    {
        return $this->stopPointCurrentLon;
    }

    /**
     * @param float $stopPointCurrentLon
     * @return LocationPatch
     */
    public function setStopPointCurrentLon(float $stopPointCurrentLon): LocationPatch
    {
        $this->stopPointCurrentLon = $stopPointCurrentLon;
        return $this;
    }

    /**
     * @return float
     */
    public function getStopPointPatchedLat(): float
    {
        return $this->stopPointPatchedLat;
    }

    /**
     * @param float $stopPointPatchedLat
     * @return LocationPatch
     */
    public function setStopPointPatchedLat(float $stopPointPatchedLat): LocationPatch
    {
        $this->stopPointPatchedLat = $stopPointPatchedLat;
        return $this;
    }

    /**
     * @return float
     */
    public function getStopPointPatchedLon(): float
    {
        return $this->stopPointPatchedLon;
    }

    /**
     * @param float $stopPointPatchedLon
     * @return LocationPatch
     */
    public function setStopPointPatchedLon(float $stopPointPatchedLon): LocationPatch
    {
        $this->stopPointPatchedLon = $stopPointPatchedLon;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouteId(): string
    {
        return $this->routeId;
    }

    /**
     * @param string $routeId
     * @return LocationPatch
     */
    public function setRouteId(string $routeId): LocationPatch
    {
        $this->routeId = $routeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     * @return LocationPatch
     */
    public function setRouteName(string $routeName): LocationPatch
    {
        $this->routeName = $routeName;
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
     * @return LocationPatch
     */
    public function setUserGeolocationLat(float $userGeolocationLat): LocationPatch
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
     * @return LocationPatch
     */
    public function setUserGeolocationLon(float $userGeolocationLon): LocationPatch
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
     * @return LocationPatch
     */
    public function setUserGpsAccuracy(float $userGpsAccuracy): LocationPatch
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
     * @return LocationPatch
     */
    public function setUser(User $user): LocationPatch
    {
        $this->user = $user;
        return $this;
    }

    public function jsonSerialize() : array
    {
        return [
            'created_at' => $this->createdAt->format(\DateTime::ISO8601),
            'source_name' => $this->sourceName,
            'patched_using_geoloc' => $this->usingUserGeolocation,
            'stop_point' => [
                'id' => $this->stopPointId,
                'name' => $this->stopPointName
            ],
            'stop_point_current_location' => [
                'lat' => $this->stopPointCurrentLat,
                'lon' => $this->stopPointCurrentLon,
            ],
            'stop_point_patched_location' => [
                'lat' => $this->stopPointPatchedLat,
                'lon' => $this->stopPointPatchedLon,
            ],
            'route' => [
                'id' => $this->routeId,
                'name' => $this->routeName
            ],
            'gps' => [
                'location' => [
                    'lat' => $this->userGeolocationLat,
                    'lon' => $this->userGeolocationLon
                ],
                'accuracy' => $this->userGpsAccuracy
            ],
        ];
    }
}
