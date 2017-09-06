<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class EquipmentExists
 * @Annotation
 */
class ElevatorExists extends Constraint
{
    public $messageElevatorNotFound = 'Unable to find any elevator with the code \'{{ code }}\'';
}
