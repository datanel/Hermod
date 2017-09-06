<?php

namespace AppBundle\Model\Api\Json\Document;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class Elevator
{
    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\Source")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\Source")
     */
    private $source;

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function setCode($code) : Elevator
    {
        $this->code = $code;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }
}
