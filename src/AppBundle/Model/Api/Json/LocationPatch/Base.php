<?php

namespace AppBundle\Model\Api\Json\LocationPatch;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * TODO: Transform this class to abstract and extend it from StopPoint and Elevator. More information here: https://github.com/schmittjoh/JMSSerializerBundle/issues/292
 * @JMS\Discriminator(
 *     field = "type",
 *     map = {
 *      "stop_point": "AppBundle\Model\Api\Json\LocationPatch\StopPoint",
 *      "elevator": "AppBundle\Model\Api\Json\LocationPatch\Elevator"
 *     })
 */
class Base
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^stop_point|elevator/")
     * @Assert\Type("string")
     */
    private $type;
}
