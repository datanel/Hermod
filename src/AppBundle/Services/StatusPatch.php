<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Model\Api\Json\Document\Elevator as ElevatorDocument;
use AppBundle\Entity\StatusPatch as StatusPatchEntity;
use AppBundle\Entity\User;

class StatusPatch
{
    private $em;
    private $equipmentEntity;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->equipmentEntity = null;
    }

    private function createStatus(User $user, $data)
    {
        $statusEntity = new StatusPatchEntity();

        $statusEntity
            ->setUser($user)
            ->setEquipmentId($this->equipmentEntity->getId())
            ->setCurrentStatus($data->getCurrentStatus())
            ->setPatchedStatus($data->getPatchedStatus())
        ;
        $this->em->persist($statusEntity);
    }

    private function findElevator(ElevatorDocument $elevator)
    {
        $elevatorEntity = $this->em->getRepository('AppBundle:Elevator')
            ->findOneBy([
                'code' => $elevator->getCode(),
                'sourceName' => $elevator->getSource()->getName()
            ])
        ;

        return $elevatorEntity;
    }

    public function create(User $user, $data)
    {
        switch ($data->getType()) {
            case 'elevator':
                $this->equipmentEntity = $this->findElevator($data->getElevator());
                break;
        }
        $this->createStatus($user, $data);
        $this->em->flush();
    }
}