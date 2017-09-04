<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Model\Api\Json\Document\StopPoint as StopPointDocument;
use AppBundle\Model\Api\Json\Document\Elevator as ElevatorDocument;
use AppBundle\Entity\Location as LocationEntity;
use AppBundle\Entity\User;

class LocationPatch
{
    private $em;
    private $equipmentEntity;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->equipmentEntity = null;
    }

    private function createLocation(User $user, $data, bool $withUserLocation)
    {
        $locationEntity = new LocationEntity();

        $locationEntity
            ->setUser($user)
            ->setEquipmentId($this->equipmentEntity->getId())
            ->setCurrentLat($data->getCurrentLocation()->getLat())
            ->setCurrentLon($data->getCurrentLocation()->getLon())
            ->setPatchedLat($data->getPatchedLocation()->getLat())
            ->setPatchedLon($data->getPatchedLocation()->getLon())
            ->setReporterLat($data->getReporterLocation()->getLocation()->getLat())
            ->setReporterLon($data->getReporterLocation()->getLocation()->getLon())
            ->setReporterAccuracy($data->getReporterLocation()->getAccuracy())
            ->setUsingReporterGeolocation($withUserLocation)
        ;
        $this->em->persist($locationEntity);
    }

    private function findOrCreateStopPoint(StopPointDocument $stopPoint)
    {
        $stopPointEntity = $this->em->getRepository('AppBundle:StopPoint')
            ->findOrCreateByCode($stopPoint->getId())
        ;

        $stopPointEntity
            ->setName($stopPoint->getName())
            ->setRouteId($stopPoint->getRoute()->getId())
            ->setRouteName($stopPoint->getRoute()->getName())
            ->setCode($stopPoint->getId())
            ->setSourceName($stopPoint->getSource()->getName())
        ;
        $this->em->persist($stopPointEntity);

        return $stopPointEntity;
    }

    private function findElevator(ElevatorDocument $elevator)
    {
        $elevatorEntity = $this->em->getRepository('AppBundle:Elevator')
            ->findOneBy([
                'code' => $elevator->getId(),
                'sourceName' => $elevator->getSource()->getName()
            ])
        ;

        return $elevatorEntity;
    }

    public function create(User $user, $data, bool $withUserLocation = false)
    {
        switch ($data->getType()) {
            case 'stop_point':
                $this->equipmentEntity = $this->findOrCreateStopPoint($data->getStopPoint());
                break;
            case 'elevator':
                $this->equipmentEntity = $this->findElevator($data->getElevator());
                break;
        }
        $this->createLocation($user, $data, $withUserLocation);
        $this->em->flush();
    }
}