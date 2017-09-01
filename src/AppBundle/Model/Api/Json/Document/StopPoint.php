<?php

namespace AppBundle\Model\Api\Json\Document;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class StopPoint
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $name;

    public function getId() : ?string
    {
        return $this->id;
    }

    public function setId($id) : StopPoint
    {
        $this->id = $id;
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
}
