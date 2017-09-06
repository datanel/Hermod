<?php

namespace AppBundle\Model\Api\Json\LocationPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use AppBundle\Model\Api\Json\Document\StopPoint as StopPointDocument;

class StopPointFromReporter
{
    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Choice({"stop_point"})
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\StopPoint")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\StopPoint")
     * @JMS\SerializedName("stop_point")
     */
    private $stopPoint;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\Type("AppBundle\Model\Api\Json\LocationPatch\Location")
     * @JMS\SerializedName("current_location")
     */
    private $currentLocation;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\LocationPatch\Gps")
     * @JMS\Type("AppBundle\Model\Api\Json\LocationPatch\Gps")
     * @JMS\SerializedName("reporter_location")
     */
    private $reporterLocation;

    public function getType() : ?string
    {
        return $this->type;
    }

    public function setType($type) : StopPointFromReporter
    {
        $this->type = $type;

        return $this;
    }

    public function getStopPoint() : ?StopPointDocument
    {
        return $this->stopPoint;
    }

    public function setStopPoint($stopPoint) : StopPointFromReporter
    {
        $this->stopPoint = $stopPoint;

        return $this;
    }

    public function getCurrentLocation() : ?Location
    {
        return $this->currentLocation;
    }

    public function setCurrentLocation($currentLocation) : StopPointFromReporter
    {
        $this->currentLocation = $currentLocation;

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
