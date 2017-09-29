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

    public function getCsvReportByPeriod(int $day)
    {
        $locationPatches = $this->em->getRepository('AppBundle:LocationPatch')->findByPeriod($day);
        $csv = "username;equipment_name;equipment_code;equipment_source_name;using_reporter_geolocation;current_lat;current_lon;patched_lat;patched_lon;";
        $csv .= "reporter_lat;reporter_lon;reporter_accuracy;created_at\n";

        foreach ($locationPatches as $locationPatch) {
            $csv .= $locationPatch[0]->getUser()->getUsername() . ';';
            $csv .= $locationPatch['equipment_name'] . ';';
            $csv .= $locationPatch['equipment_code'] . ';';
            $csv .= $locationPatch['equipment_source_name'] . ';';
            $csv .= $locationPatch[0]->isUsingReporterGeolocation() ? 'yes;' : 'no;';
            $csv .= $locationPatch[0]->getCurrentLat() . ';';
            $csv .= $locationPatch[0]->getCurrentLon() . ';';
            $csv .= $locationPatch[0]->getPatchedLat() . ';';
            $csv .= $locationPatch[0]->getPatchedLon() . ';';
            $csv .= $locationPatch[0]->getReporterLat() . ';';
            $csv .= $locationPatch[0]->getReporterLon() . ';';
            $csv .= $locationPatch[0]->getReporterAccuracy() . ';';
            $csv .= $locationPatch[0]->getCreatedAt()->format('d/m/Y') . ';';
            $csv .= "\n";
        }

        return $csv;
    }
}