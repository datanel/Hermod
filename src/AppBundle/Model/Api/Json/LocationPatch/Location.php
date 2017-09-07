<?php

namespace AppBundle\Model\Api\Json\LocationPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use AppBundle\Validator as AppAssert;

class Location
{
    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @Assert\Range(
     *      min = -90,
     *      max = 90,
     *      minMessage = "Your latitude 'lat' is not a valid WGS84 (must be at least {{ limit }})",
     *      maxMessage = "Your latitude 'lat' is not a valid WGS84 (must be at most {{ limit }})"
     * )
     * @JMS\Type("float")
     */
    private $lat;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @Assert\Range(
     *      min = -180,
     *      max = 180,
     *      minMessage = "Your longitude 'lon' is not a valid WGS84 (must be at least {{ limit }})",
     *      maxMessage = "Your longitude 'lon' is not a valid WGS84 (must be at most {{ limit }})"
     * )
     * @JMS\Type("float")
     */
    private $lon;

    public function getLat() : ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat) : Location
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon() : ?float
    {
        return $this->lon;
    }

    public function setLon($lon) : Location
    {
        $this->lon = $lon;

        return $this;
    }
}
