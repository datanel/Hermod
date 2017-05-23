<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Entity\StopPointLocationPatch;
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
 * @Route("/stop_point_location_patches")
 */
class StopPointLocationPatchController extends BaseController
{
    /**
     * @Route("", name="v1_get_all_stop_point_location_patches")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return new JsonResponse($this->getDoctrine()->getRepository('AppBundle:StopPointLocationPatch')->findAll());
    }

    /**
     * @Route("", name="v1_create_stop_point_location_patch")
     * @Method("POST")
     */
    public function createPatchAction(Request $request)
    {
        return $this->createPatch($request, false);
    }

    /**
     * @Route("/with_user_location", name="v1_create_stop_point_location_patch_with_user_location")
     * @Method("POST")
     */
    public function createPatchUsingGeolocation(Request $request)
    {
        return $this->createPatch($request, true);
    }

    /**
     * Validates the given information and creates a stop point location patch in database
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
        $stopPointLocationPatch = StopPointLocationPatch::createFromApiInput($inputData);
        $this->getDoctrine()->getManager()->persist($stopPointLocationPatch);
        $this->getDoctrine()->getManager()->flush();

        return new ResourceCreatedResponse();
    }

    /**
     * @param bool $usingUserGeolocation whether to expect the user to give his position
     *
     * @return Constraint the constraint validating the user input
     */
    private function createPatchConstraint(bool $usingUserGeolocation) : Constraint
    {
        $constraints = [
            'source' => new Collection([
                'type' => new Required([
                    new NotBlank(),
                    new Choice([
                        'choices' => ['nav_1'],
                        'message' => '{{ value }} is not a valid source type'
                    ])
                ]),
                'instance_name' => new NotBlank()
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
        if ($usingUserGeolocation) {
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
