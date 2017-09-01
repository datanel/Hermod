<?php

namespace AppBundle\Model\Api\Json\LocationPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class StopPoint
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^stop_point/")
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\StopPoint")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\StopPoint")
     * @JMS\SerializedName("stop_point")
     */
    private $stopPoint;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\Source")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\Source")
     */
    private $source;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\Route")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\Route")
     */
    private $route;

    /**
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\SerializedName("current_location")
     */
    private $currentLocation;

    /**
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\SerializedName("patched_location")
     */
    private $patchedLocation;

    /**
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\LocationPatch\Gps")
     * @JMS\Type("AppBundle\Model\Api\Json\LocationPatch\Gps")
     * @JMS\SerializedName("reported_location")
     */
    private $reporterLocation;

    public function getType() : ?string
    {
        return $this->type;
    }

    public function setType($type) : StopPoint
    {
        $this->type = $type;

        return $this;
    }

    public function getStopPoint() : ?StopPointDocument
    {
        return $this->stopPoint;
    }

    public function setStopPoint($stopPoint) : StopPoint
    {
        $this->stopPoint = $stopPoint;

        return $this;
    }

    public function getCurrentLocation() : ?Location
    {
        return $this->currentLocation;
    }

    public function setCurrentLocation($currentLocation) : StopPoint
    {
        $this->currentLocation = $currentLocation;

        return $this;
    }

    public function getPatchedLocation() : ?Location
    {
        return $this->patchedLocation;
    }

    public function setPatchedLocation($patchedLocation) : Location
    {
        $this->patchedLocation = $patchedLocation;

        return $this;
    }

    public function getReporterLocation() : Gps
    {
        return $this->reporterLocation;
    }

    public function setReporterLocation($reporterLocation)
    {
        $this->reporterLocation = $reporterLocation;

        return $this;
    }
}
