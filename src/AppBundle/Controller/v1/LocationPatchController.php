<?php

namespace AppBundle\Controller\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Services\LocationPatch as LocationPatchService;
use AppBundle\Model\Api\Json\LocationPatch\Base as LocationPatchModel;
use AppBundle\Model\Api\Json\LocationPatch\StopPoint as StopPointLocationPatchModel;

/**
 * @Route("/patches/location")
 */
class LocationPatchController extends BaseController
{
    /**
     * @Route("/_all", name="v1_get_all_location_patches")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return new JsonResponse(
            $this->getDoctrine()
                ->getRepository('AppBundle:LocationPatch')
                ->findBy(['user' => $this->getUser()])
        );
    }

    /**
     * @Route("", name="v1_create_location_patch")
     * @Method("POST")
     */
    public function createPatchAction(Request $request, LocationPatchService $locationPatchService)
    {
        $result = $this->deserializeOr400($request->getContent(), LocationPatchModel::class);
        $this->validOr400($result);
        $locationPatchService->create($this->getUser(), $result);

        return new ResourceCreatedResponse();
    }

    /**
     * @Route("/from_user", name="v1_create_location_patch_from_user_location")
     * @Method("POST")
     */
    public function createPatchFromUser(Request $request, LocationPatchService $locationPatchService)
    {
        $result = $this->deserializeOr400($request->getContent(), StopPointLocationPatchModel::class);
        $this->validOr400($result);
        $locationPatchService->create($this->getUser(), $result, true);

        return new ResourceCreatedResponse();
    }
}
