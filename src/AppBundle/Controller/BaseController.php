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
     * Shortcut method
     * @param $entity
     */
    public function save($entity)
    {
        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();
    }

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
     * @return array the json decoded input
     * @throws BadRequestException when the content of the request is not a valid json string
     */
    public function getInputContent(Request $request) : array
    {
        $inputContent = json_decode($request->getContent(), true);
        if ($inputContent === null) {
            throw new BadRequestException('The provided input content is not a valid json string', 'invalid_params');
        }
        return $inputContent;
    }

    /**
     * Takes a violation list as input and returns a list of errors formatted as follow:
     * Input error for param property_path: error_string
     *
     * TODO: This is a naive implementation as it just outputs a slightly modified property path for
     * each violation (ex: [field_a->field_b]).
     * This is highly tied to the PropertyAccessor notation
     *
     * @param ConstraintViolationListInterface $violationList
     *
     * @return array
     */
    protected function flattenViolations(ConstraintViolationListInterface $violationList) : array
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
