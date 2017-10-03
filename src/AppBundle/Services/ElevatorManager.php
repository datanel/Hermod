<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Model\Api\Json\Document\Elevator as ElevatorDocument;

class ElevatorManager
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findAll()
    {
        $statusRepository = $this->em->getRepository('AppBundle:StatusPatch');
        $locationRepository = $this->em->getRepository('AppBundle:LocationPatch');
        $elevators = $this->em->getRepository('AppBundle:Elevator')->findAll();

        foreach ($elevators as $elevator) {
            $elevator->setStatusPatches(
                $statusRepository->findByEquipmentId($elevator->getId(), ['createdAt' => 'DESC'], 5)
            );
            $elevator->setLocationPatches(
                $locationRepository->findByEquipmentId($elevator->getId(), ['createdAt' => 'DESC'], 5)
            );
        }

        return $elevators;
    }

    public function findOrCreate(ElevatorDocument $elevator)
    {
        $elevatorEntity = $this->em->getRepository('AppBundle:Elevator')
            ->findOrCreate($elevator->getCode(), $elevator->getSource()->getName())
        ;

        $elevatorEntity
            ->setName($elevator->getName())
            ->setStationId($elevator->getStation()->getId())
            ->setStationName($elevator->getStation()->getName())
            ->setDirection($elevator->getDirection())
            ->setHumanLocation($elevator->getHumanLocation())
            ->setCode($elevator->getCode())
            ->setSourceName($elevator->getSource()->getName())
        ;
        $this->em->persist($elevatorEntity);

        return $elevatorEntity;
    }
}