<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Equipment;
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
        $mandatoryHeaders = ['ID', 'Code metier', 'ID Gare', 'Gare', 'Situation', 'Direction'];
        try {
            $csv = Csv::parse($request->getContent(), $mandatoryHeaders);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestException($e->getMessage());
        }
        $equipments = [];

        foreach ($csv as $row) {
            $equipments[] = (new Equipment($row['ID']))
                ->setCode($row['Code metier'])
                ->setType(Equipment::TYPE_ELEVATOR)
                ->setStationId($row['ID Gare'])
                ->setStationName($row['Gare'])
                ->setLocation($row['Situation'])
                ->setDirection($row['Direction']);
        }

        return $this->json($this->createOrUpdateEach($equipments));
    }

    /**
     * Saves a collection of equipments in the database.
     * If the equipment already exist, we update it only if his information differ
     * from the provided ones. If it does not exist, we just create it
     *
     * @param array $equipments
     *
     * @return array an associative array containing the created and updated records
     */
    private function createOrUpdateEach(array $equipments)
    {
        $created = $updated = [];
        $em = $this->getDoctrine()->getManager();
        $existingEquipments = $em->createQuery('SELECT e FROM AppBundle:Equipment e INDEX BY e.id')->getResult();

        /** @var Equipment $equipment */
        foreach ($equipments as $equipment) {
            /** @var Equipment $equipmentInDb */
            $equipmentInDb = $existingEquipments[$equipment->getId()] ?? null;
            if ($equipmentInDb) { // this equipment already exists in DB
                if (!$equipment->equals($equipmentInDb)) {
                    $equipmentInDb->updateFrom($equipment);
                    $em->persist($equipmentInDb);
                    $updated[] = $equipmentInDb;
                }
            } else {
                $em->persist($equipment);
                $created[] = $equipment;
            }
        }

        $em->flush();

        return ['created' => $created, 'updated' => $updated];
    }
}
