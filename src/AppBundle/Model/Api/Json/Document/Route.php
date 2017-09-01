<?php

namespace AppBundle\Model\Api\Json\Document;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class Route
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

    public function setId($id) : Route
    {
        $this->id = $id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName($name) : Route
    {
        $this->name = $name;

        return $this;
    }
}
