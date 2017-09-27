<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\StatusPatch as StatusPatchEntity;
use AppBundle\Entity\User;

class StatusPatch
{
    private $em;
    private $equipmentEntity;
    private $elevatorManager;

    function __construct(EntityManagerInterface $em, ElevatorManager $elevatorManager)
    {
        $this->em = $em;
        $this->equipmentEntity = null;
        $this->elevatorManager = $elevatorManager;
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

    public function create(User $user, $data)
    {
        switch ($data->getType()) {
            case 'elevator':
                $this->equipmentEntity = $this->elevatorManager->findOrCreate($data->getElevator());
                break;
        }
        $this->createStatus($user, $data);

        $this->em->flush();
    }
}