<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * This class implements a work-around for https://github.com/symfony/symfony/issues/16412
 * Instead of adding a violation when a collection is not an array, the original constraint
 * throws a runtime exception. So we can not validate API input properly, as the user may
 * send unexpected type where we expect an array
 */
class CollectionValidator extends \Symfony\Component\Validator\Constraints\CollectionValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Collection) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Collection');
        }

        if (null === $value) {
            return;
        }

        if (!is_array($value) && !($value instanceof \Traversable && $value instanceof \ArrayAccess)) {
            // !! work-around is here !!
            $this->context
                ->buildViolation($constraint->shouldBeArrayMessage)
                ->addViolation();
            return;
            // !! end of work-around !!
        }

        // We need to keep the initialized context when CollectionValidator
        // calls itself recursively (Collection constraints can be nested).
        // Since the context of the validator is overwritten when initialize()
        // is called for the nested constraint, the outer validator is
        // acting on the wrong context when the nested validation terminates.
        //
        // A better solution - which should be approached in Symfony 3.0 - is to
        // remove the initialize() method and pass the context as last argument
        // to validate() instead.
        $context = $this->context;

        foreach ($constraint->fields as $field => $fieldConstraint) {
            // bug fix issue #2779
            $existsInArray = is_array($value) && array_key_exists($field, $value);
            $existsInArrayAccess = $value instanceof \ArrayAccess && $value->offsetExists($field);

            if ($existsInArray || $existsInArrayAccess) {
                if (count($fieldConstraint->constraints) > 0) {
                    $context->getValidator()
                        ->inContext($context)
                        ->atPath('['.$field.']')
                        ->validate($value[$field], $fieldConstraint->constraints);
                }
            } elseif (!$fieldConstraint instanceof Optional && !$constraint->allowMissingFields) {
                $context->buildViolation($constraint->missingFieldsMessage)
                    ->atPath('['.$field.']')
                    ->setParameter('{{ field }}', $this->formatValue($field))
                    ->setInvalidValue(null)
                    ->setCode(Collection::MISSING_FIELD_ERROR)
                    ->addViolation();
            }
        }

        if (!$constraint->allowExtraFields) {
            foreach ($value as $field => $fieldValue) {
                if (!isset($constraint->fields[$field])) {
                    $context->buildViolation($constraint->extraFieldsMessage)
                        ->atPath('['.$field.']')
                        ->setParameter('{{ field }}', $this->formatValue($field))
                        ->setInvalidValue($fieldValue)
                        ->setCode(Collection::NO_SUCH_FIELD_ERROR)
                        ->addViolation();
                }
            }
        }
    }
}
