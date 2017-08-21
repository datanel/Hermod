<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\EquipmentRepository;
use AppBundle\Http\Exception\BadRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util\Csv;

/**
 * @Route("/equipments")
 */
class EquipmentController extends BaseController
{
    /**
     * @Route("/_all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return $this->json(
            $this->getDoctrine()->getRepository('AppBundle:Equipment')->findAll()
        );
    }

    /**
     * @Route("/csv_update")
     * @Method("POST")
     */
    public function csvUpdateAction(Request $request)
    {
        $mandatoryHeaders = ['id', 'code', 'station_id', 'station', 'status', 'direction'];
        try {
            $csv = Csv::parse($request->getContent(), $mandatoryHeaders);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestException($e->getMessage());
        }
        $equipments = [];

        foreach ($csv as $row) {
            $equipments[] = (new Equipment($row['id']))
                ->setCode($row['code'])
                ->setType(Equipment::TYPE_ELEVATOR)
                ->setStationId($row['station_id'])
                ->setStationName($row['station'])
                ->setLocation($row['status'])
                ->setDirection($row['direction']);
        }

        return $this->json($this->getDoctrine()->getRepository(Equipment::class)->createOrUpdateEach($equipments));
    }
}
