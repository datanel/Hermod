<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Entity\LocationPatch;
use AppBundle\Validator\{
    Collection, Wgs84Coord
};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\{
    Choice, NotBlank, Optional, Range, Required
};

/**
 * @Route("/location_patches")
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
    public function createPatchAction(Request $request)
    {
        return $this->createPatch($request, false);
    }

    /**
     * @Route("/from_user_location", name="v1_create_location_patch_from_user_location")
     * @Method("POST")
     */
    public function createPatchUsingGeolocation(Request $request)
    {
        return $this->createPatch($request, true);
    }

    /**
     * Validates the given information and creates a location patch in database
     *
     * @param Request $request
     * @param bool $usingUserGeolocation
     *
     * @return ResourceCreatedResponse
     */
    private function createPatch(Request $request, bool $usingUserGeolocation)
    {
        $inputData = $this->getInputContent($request);
        $this->validOr400($inputData, $this->createPatchConstraint($usingUserGeolocation));
        $stopPointLocationPatch = LocationPatch::createFromApiInput($inputData, $this->getUser());
        $this->getDoctrine()->getManager()->persist($stopPointLocationPatch);
        $this->getDoctrine()->getManager()->flush();

        return new ResourceCreatedResponse();
    }

    /**
     * @param bool $fromUserLocation whether to create the patch from the user geolocation
     *
     * @return Constraint the constraint validating the user input
     */
    private function createPatchConstraint(bool $fromUserLocation) : Constraint
    {
        $constraints = [
            'source' => new Collection([
                'name' => new NotBlank()
            ]),
            'stop_point' => new Collection([
                'id' => new NotBlank(),
                'name' => new Optional(new NotBlank()),
            ]),
            'stop_point_current_location' => new Wgs84Coord(),
            'route' => new Collection([
                'id' => new NotBlank(),
                'name' => new Optional(new NotBlank()),
            ]),
        ];
        $gpsConstraint = new Collection([
            'location' => new Wgs84Coord(),
            'accuracy' => new Required([
                new NotBlank(),
                new Range(['min' => 0])
            ]),
        ]);
        if ($fromUserLocation) {
            $constraints = array_merge($constraints, ['gps' => new Required($gpsConstraint)]);
        } else {
            $constraints = array_merge($constraints, [
                'stop_point_patched_location' => new Wgs84Coord(),
                'gps' => new Optional($gpsConstraint)
            ]);
        }
        return new Collection($constraints);
    }
}
