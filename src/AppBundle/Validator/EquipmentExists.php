<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class EquipmentExists extends Constraint
{
    public $messageEquipmentNotFound = 'Unable to find any equipment of type \'{{ type }}\' with the id \'{{ id }}\'';
}
