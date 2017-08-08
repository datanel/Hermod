<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Required;

/**
 * Provide methods to create validation constraints shared across multiple API endpoints
 */
class CommonConstraints
{
    public static function Gps() : Constraint
    {
        return new Collection([
            'location' => new Wgs84Coord(),
            'accuracy' => new Required([
                new NotBlank(),
                new Range(['min' => 0])
            ]),
        ]);
    }
}
