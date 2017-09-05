<?php

namespace AppBundle\Validator;

use AppBundle\Model\Api\Json\Document\Elevator as ElevatorDocument;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ElevatorExistsValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (is_null($value) || !($value instanceof ElevatorDocument)) {
            return;
        }
        $elevator = $this->em->getRepository('AppBundle:Elevator')->findOneBy([
            'code' => $value->getCode(),
            'sourceName' => $value->getSource()->getName()
        ]);

        if (is_null($elevator)) {
            $this->context
                ->buildViolation($constraint->messageElevatorNotFound)
                ->setParameter('{{ code }}', $value->getCode())
                ->addViolation();
        }
    }
}
