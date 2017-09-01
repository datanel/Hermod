<?php

namespace AppBundle\Model\Api\Json\LocationPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use AppBundle\Validator as AppAssert;

class Location
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @Assert\Length(
     *      min = -90,
     *      max = 90,
     *      minMessage = "Your latitude 'lat' is not a valid WGS84 (must greater than {{ limit }})",
     *      maxMessage = "Your latitude 'lon' is not a valid WGS84 (must smaller than {{ limit }})"
     * )
     * @JMS\Type("float")
     */
    private $lat;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @Assert\Length(
     *      min = -180,
     *      max = 180,
     *      minMessage = "Your longitude 'lon' is not a valid WGS84 (must greater than {{ limit }})",
     *      maxMessage = "Your longitude 'lon' is not a valid WGS84 (must smaller than {{ limit }})"
     * )
     * @JMS\Type("float")
     */
    private $lon;

    /**
     * @return mixed
     */
    public function getLat() : ?float
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat(float $lat) : Location
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLon() : ?float
    {
        return $this->lon;
    }

    /**
     * @param mixed $lon
     */
    public function setLon($lon) : Location
    {
        $this->lon = $lon;

        return $this;
    }
}
