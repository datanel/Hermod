<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class Wgs84CoordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        foreach (['lat', 'lon'] as $key) {
            if (!isset($value[$key])) {
                $this->context->buildViolation($constraint->messageMissingField)
                    ->atPath(sprintf('[%s]', $key))
                    ->addViolation();
                continue;
            }
            if (!is_numeric($value[$key])) {
                $this->context->buildViolation($constraint->messageWrongType)
                    ->atPath(sprintf('[%s]', $key))
                    ->addViolation();
                continue;
            }
            if ($key == 'lat' && ($value[$key] < -90 || $value[$key] > 90)) {
                $this->context->buildViolation($constraint->messageWrongLat)
                    ->setParameter('{{ value }}', $value[$key])
                    ->addViolation();
            }
            if ($key == 'lon' && ($value[$key] < -180 || $value[$key] > 180)) {
                $this->context->buildViolation($constraint->messageWrongLon)
                    ->setParameter('{{ value }}', $value[$key])
                    ->addViolation();
            }
        }
    }
}
