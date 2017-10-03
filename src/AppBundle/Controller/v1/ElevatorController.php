<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Services\ElevatorManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/elevators")
 */
class ElevatorController extends BaseController
{
    /**
     * @Security("has_role('ROLE_V1_GET_EQUIPMENTS')")
     * @Route("/")
     * @Method("GET")
     */
    public function getAllAction(ElevatorManager $elevatorManager)
    {
        return $this->json($elevatorManager->findAll());
    }
}
