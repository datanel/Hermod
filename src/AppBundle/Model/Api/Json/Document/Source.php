<?php

namespace AppBundle\Model\Api\Json\Document;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class Source
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @JMS\Type("string")
     */
    private $name;

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName($name) : Source
    {
        $this->name = $name;

        return $this;
    }
}
