<?php

namespace AppBundle\Model\Api\Json\LocationPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use AppBundle\Validator as AppAssert;

class Gps
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @JMS\Type("float")
     */
    private $accuracy;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     */
    private $location;

    public function getAccuracy() : ?float
    {
        return $this->accuracy;
    }

    public function setAccuracy($accuracy) : Gps
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getLocation() : ?float
    {
        return $this->location;
    }

    public function setLocation($location) : Gps
    {
        $this->location = $location;

        return $this;
    }


}
