<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class Wgs84Coord extends Constraint
{
    public $messageWrongType = 'Invalid numeric value';
    public $messageWrongLat = '{{ value }} is not a valid WGS84 latitude (must be in [-90,90])';
    public $messageWrongLon = '{{ value }} is not a valid WGS84 longitude (must be in [-180,180])';
    public $messageMissingField = 'This field is missing';
}
