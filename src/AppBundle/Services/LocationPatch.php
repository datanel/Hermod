<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\LocationPatch as LocationPatchEntity;
use AppBundle\Entity\User;

class LocationPatch
{
    private $em;
    private $stopPointManager;
    private $elevatorManager;
    private $equipmentEntity;

    function __construct(EntityManagerInterface $em, StopPointManager $stopPointManager, ElevatorManager $elevatorManager)
    {
        $this->em = $em;
        $this->elevatorManager = $elevatorManager;
        $this->stopPointManager = $stopPointManager;
        $this->equipmentEntity = null;
    }

    private function createLocation(User $user, $data, bool $withReporterLocation)
    {
        $locationEntity = new LocationPatchEntity();

        $locationEntity
            ->setUser($user)
            ->setEquipmentId($this->equipmentEntity->getId())
            ->setCurrentLat($data->getCurrentLocation()->getLat())
            ->setCurrentLon($data->getCurrentLocation()->getLon())
            ->setReporterLat($data->getReporterLocation()->getLocation()->getLat())
            ->setReporterLon($data->getReporterLocation()->getLocation()->getLon())
            ->setReporterAccuracy($data->getReporterLocation()->getAccuracy())
            ->setUsingReporterGeolocation($withReporterLocation)
        ;
        if ($withReporterLocation === false) {
            $locationEntity
                ->setPatchedLat($data->getPatchedLocation()->getLat())
                ->setPatchedLon($data->getPatchedLocation()->getLon())
            ;

        }
        $this->em->persist($locationEntity);
    }

    public function create(User $user, $data, bool $withReporterLocation = false)
    {
        switch ($data->getType()) {
            case 'stop_point':
                $this->equipmentEntity = $this->stopPointManager->findOrCreate($data->getStopPoint());
                break;
            case 'elevator':
                $this->equipmentEntity = $this->elevatorManager->findOrCreate($data->getElevator());
                break;
        }
        $this->createLocation($user, $data, $withReporterLocation);
        $this->em->flush();
    }
}