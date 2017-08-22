<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EquipmentRepository extends EntityRepository
{
    /**
     * Saves a collection of equipments in the database.
     * If the equipment already exists, we update it only if its information differs
     * from the provided ones. If it does not exist, we just create it
     *
     * @param array $equipments
     *
     * @return array an associative array containing the created and updated records
     */
    public function createOrUpdateEach(array $equipments) : array
    {
        $created = $updated = [];
        $em = $this->getEntityManager();
        $existingEquipments = $em->createQuery('SELECT e FROM AppBundle:Equipment e INDEX BY e.id')->getResult();

        /** @var Equipment $equipment */
        foreach ($equipments as $equipment) {
            /** @var Equipment $equipmentInDb */
            $equipmentInDb = $existingEquipments[$equipment->getId()] ?? null;
            if ($equipmentInDb) {
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
