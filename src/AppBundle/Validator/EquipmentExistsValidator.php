<?php

namespace AppBundle\Validator;

use AppBundle\Entity\Equipment;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EquipmentExistsValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        // ignore invalid inputs as we do not want redundant error messages.
        // input should be validated against other constraints !
        if (!isset($value['id']) ||
            !isset($value['type']) ||
            !in_array($value['type'], Equipment::getAvailableTypes())
        ) {
            return;
        }
        $equipment = $this->em->getRepository('AppBundle:Equipment')->findOneBy([
            'id' => (int)$value['id'],
            'type' => $value['type']
        ]);

        if (!$equipment) {
            $this->context
                ->buildViolation($constraint->messageEquipmentNotFound)
                ->setParameter('{{ id }}', $value['id'])
                ->setParameter('{{ type }}', $value['type'])
                ->addViolation();
        }
    }
}
