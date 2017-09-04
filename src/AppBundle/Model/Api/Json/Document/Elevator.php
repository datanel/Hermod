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
    private $id;

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

    public function getId() : ?string
    {
        return $this->id;
    }

    public function setId($id) : Elevator
    {
        $this->id = $id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName($name) : Elevator
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
}
