<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Elevator;
use AppBundle\Http\Exception\BadRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/elevators")
 */
class ElevatorController extends BaseController
{
    /**
     * @Security("has_role('ROLE_ROOT')")
     * @Route("/_all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return $this->json(
            $this->getDoctrine()->getRepository('AppBundle:Elevator')->findAll()
        );
    }
}
