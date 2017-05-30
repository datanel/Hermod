<?php

namespace AppBundle\Controller;

use AppBundle\Http\Exception\BadRequestException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class BaseController extends Controller
{
    /**
     * Utility method allowing early return in case of bad request from the client.
     *
     * @param mixed $value the value to validate against the given constraints
     * @param Constraint|Constraint[] $constraints
     * @param string[]|null $groups the validation groups to use
     *
     * @throws BadRequestException When the given input value is not valid
     */
    public function validOr400($value, $constraints, $groups = null)
    {
        $violations = $this->get('validator')->validate($value, $constraints, (array)$groups);

        if (count($violations)) {
            throw new BadRequestException($this->flattenViolations($violations), 'invalid_params');
        }
    }

    /**
     * Utility method allowing to retrieve the given json request content
     *
     * @param Request $request
     *
     * @return array the json decoded input or an empty array if the input is invalid
     */
    public function getInputContent(Request $request) : array
    {
        return (array)json_decode($request->getContent(), true);
    }

    /**
     * Takes a violation list as input and returns a list of errors formatted as follow:
     * Input error for param property_path: error_string
     *
     * TODO: This is a naive implementation as it just outputs a slightly modified property path for
     * each violation (ex: [field_a->field_b]).
     * This is highly tied with the PropertyAccessor notation
     *
     * @param ConstraintViolationListInterface $violationList
     *
     * @return array
     */
    private function flattenViolations(ConstraintViolationListInterface $violationList) : array
    {
        $errors = array();
        foreach ($violationList as $violation) {
            $errors[] = sprintf(
                'Error at %s: %s',
                str_replace('][', '->', $violation->getPropertyPath()),
                $violation->getMessage()
            );
        }

        return $errors;
    }
}
