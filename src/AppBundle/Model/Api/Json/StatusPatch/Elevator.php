<?php

namespace AppBundle\Model\Api\Json\StatusPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use AppBundle\Validator as AppAssert;
use AppBundle\Model\Api\Json\Document\Elevator as ElevatorDocument;
use AppBundle\Validator\ElevatorExists as AppAssertElevatorExist;

class Elevator
{
    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Regex("/^elevator/")
     * @Assert\Type("string")
     * @Jms\Type("string")
     */
    private $type;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\Elevator")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\Elevator")
     * @AppAssertElevatorExist
     */
    private $elevator;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Regex("/^available|unavailable|coming_soon|disturbed/")
     * @Assert\Type("string")
     * @JMS\Type("string")
     * @JMS\SerializedName("current_status")
     */
    private $currentStatus;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Regex("/^available|unavailable|coming_soon|disturbed/")
     * @Assert\Type("string")
     * @JMS\Type("string")
     * @JMS\SerializedName("patched_status")
     */
    private $patchedStatus;

    public function getType() : ?string
    {
        return $this->type;
    }

    public function setType($type) : Elevator
    {
        $this->type = $type;

        return $this;
    }

    public function getElevator() : ?ElevatorDocument
    {
        return $this->elevator;
    }

    public function setElevator($elevator) : Elevator
    {
        $this->elevator = $elevator;

        return $this;
    }

    public function getCurrentStatus() : ?string
    {
        return $this->currentStatus;
    }

    public function setCurrentStatus($currentStatus) : Elevator
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    public function getPatchedStatus() : ?string
    {
        return $this->patchedStatus;
    }

    public function setPatchedStatus(string $patchedStatus) : Status
    {
        $this->patchedStatus = $patchedStatus;

        return $this;
    }
}
