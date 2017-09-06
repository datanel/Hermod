<?php

namespace AppBundle\Controller\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Services\StatusPatch as StatusPatchService;
use AppBundle\Model\Api\Json\StatusPatch\Elevator as ElevatorStatusModel;

/**
 * @Route("/patches/status")
 */
class StatusPatchController extends BaseController
{
    /**
     * @Route("/_all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return $this->json(
            $this->getDoctrine()
                ->getRepository('AppBundle:StatusPatch')
                ->findBy(['user' => $this->getUser()])
        );
    }

    /**
     * @Route("")
     * @Method("POST")
     */
    public function createEquipmentStatusAction(Request $request, StatusPatchService $statusPatchService)
    {
        $result = $this->deserializeOr400($request->getContent(), ElevatorStatusModel::class);
        $this->validOr400($result);
        $statusPatchService->create($this->getUser(), $result);

        return new ResourceCreatedResponse();
    }
}
