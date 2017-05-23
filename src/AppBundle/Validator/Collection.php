<?php

namespace AppBundle\Validator;

/**
 * cf. CollectionValidator for the reason of this class
 */
class Collection extends \Symfony\Component\Validator\Constraints\Collection
{
    public $shouldBeArrayMessage = 'This field should be an array.';
}
