<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\EquipmentStatus;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Validator\{
    Collection, CommonConstraints, EquipmentExists
};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\{
    Choice, NotBlank, Optional, Required
};

/**
 * @Route("/equipment_status")
 */
class EquipmentStatusController extends BaseController
{
    /**
     * @Route("/_all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return $this->json(
            $this->getDoctrine()
                ->getRepository('AppBundle:EquipmentStatus')
                ->findBy(['user' => $this->getUser()])
        );
    }

    /**
     * @Route("")
     * @Method("POST")
     */
    public function createEquipmentStatusAction(Request $request)
    {
        $inputData = $this->getInputContent($request);
        $this->validOr400($inputData, $this->createReportConstraint());
        $this->save($this->createFromInput($inputData));

        return new ResourceCreatedResponse();
    }

    /**
     * Create an EquipmentStatus from the given input data
     * CAUTION: the input data must be validated BEFORE calling this, or it will crash badly
     *
     * @param array $inputData a valid json_decoded api input
     *
     * @return EquipmentStatus
     */
    private function createFromInput(array $inputData) : EquipmentStatus
    {
        return (new EquipmentStatus())
            ->setUser($this->getUser())
            ->setEquipment($this->getDoctrine()->getRepository('AppBundle:Equipment')->find(
                $inputData['equipment']['id'])
            )
            ->setSourceName($inputData['source']['name'])
            ->setType($inputData['type'])
            ->setReportedStatus($inputData['status'])
            ->setIncludeUserGeolocation(isset($inputData['gps']))
            ->setUserGeolocationLat($inputData['gps']['location']['lat'] ?? 0)
            ->setUserGeolocationLon($inputData['gps']['location']['lon'] ?? 0)
            ->setUserGpsAccuracy($inputData['gps']['accuracy'] ?? 0);
    }

    /**
     * @return Constraint the constraint validating the user input
     */
    private function createReportConstraint() : Constraint
    {
        return new Collection([
            'source' => new Collection([
                'name' => new NotBlank()
            ]),
            'type' => new Choice(['traveler', 'employee']),
            'equipment' =>
                [
                    new Collection([
                        'id' => new Required([new NotBlank()]),
                        'type' => new Choice(Equipment::getAvailableTypes())
                    ]),
                    new EquipmentExists()
                ],
            'status' => new Choice(['OK', 'KO']),
            'gps' => new Optional(CommonConstraints::Gps()),
        ]);
    }
}
