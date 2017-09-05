<?php

namespace AppBundle\Model\Api\Json\Document;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class StopPoint
{
    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\Source")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\Source")
     */
    private $source;

    /**
     * @Assert\NotBlank(message="This field is missing.")
     * @Assert\Valid()
     * @Assert\Type("AppBundle\Model\Api\Json\Document\Route")
     * @JMS\Type("AppBundle\Model\Api\Json\Document\Route")
     */
    private $route;

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function setCode($code) : StopPoint
    {
        $this->code = $code;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName($name) : StopPoint
    {
        $this->name = $name;

        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }
}
