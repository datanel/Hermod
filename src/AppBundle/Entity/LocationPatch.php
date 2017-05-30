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
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string The name of the source system
     */
    protected $sourceName;

    /**
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime The date and time on which this patch has been created
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean the reporting mode used, can be either
     *  - false if that patch was created by simply giving it a location
     *  - true if the end-user provided his GPS geolocation to the API to
     *    indicate the new location of the stop point
     */
    protected $usingUserGeolocation;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id of the stop point we want to patch
     */
    protected $stopPointId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id of the stop point we want to patch
     */
    protected $stopPointName;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the stop point current latitude
     */
    protected $stopPointCurrentLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the stop point current longitude
     */
    protected $stopPointCurrentLon = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the suggested latitude
     */
    protected $stopPointPatchedLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the suggested longitude
     */
    protected $stopPointPatchedLon = 0;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id of the route passing by the stop point to patch
     */
    protected $routeId;

    /**
     * @ORM\Column(type="string")
     *
     * @var string the id name the route passing by the stop point to patch
     */
    protected $routeName;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the end-user geolocation latitude
     */
    protected $userGeolocationLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the end-user geolocation longitude
     */
    protected $userGeolocationLon = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float accuracy (in meters) of the end-user GPS device
     */
    protected $userGpsAccuracy = 0;

    /**
     * Many Patches have One User.
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="patches")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User The user submitting the patch
     */
    protected $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
    }

    /**
     * Create a StopPointLocationPatch from the given input data
     * CAUTION: the input data must be validated BEFORE calling this, or it will crash badly
     *
     * @param $apiInput array a valid json_decoded api input
     * @param $user User The current user
     * @return self
     */
    public static function createFromApiInput(array $apiInput, User $user)
    {
        $spLocationPatch = new self;
        $spLocationPatch->sourceName = $apiInput['source']['name'];
        $spLocationPatch->usingUserGeolocation = !isset($apiInput['stop_point_patched_location']);
        $spLocationPatch->stopPointId = $apiInput['stop_point']['id'];
        $spLocationPatch->stopPointName = $apiInput['stop_point']['name'];

        $spLocationPatch->stopPointCurrentLat = $apiInput['stop_point_current_location']['lat'];
        $spLocationPatch->stopPointCurrentLon = $apiInput['stop_point_current_location']['lon'];

        // if this patch is a "using geolocation" patch, we copy the gps coords in the patched location
        $spLocationPatch->stopPointPatchedLat = $apiInput['stop_point_patched_location']['lat'] ??
            $apiInput['gps']['location']['lat'];
        $spLocationPatch->stopPointPatchedLon = $apiInput['stop_point_patched_location']['lon'] ??
            $apiInput['gps']['location']['lon'];

        $spLocationPatch->routeId = $apiInput['route']['id'];
        $spLocationPatch->routeName = $apiInput['route']['name'];

        $spLocationPatch->userGeolocationLat = $apiInput['gps']['location']['lat'] ??
            $spLocationPatch->userGeolocationLat;
        $spLocationPatch->userGeolocationLon = $apiInput['gps']['location']['lon'] ??
            $spLocationPatch->userGeolocationLon;
        $spLocationPatch->userGpsAccuracy = $apiInput['gps']['accuracy'] ?? $spLocationPatch->userGpsAccuracy;

        $spLocationPatch->user = $user;

        return $spLocationPatch;
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
