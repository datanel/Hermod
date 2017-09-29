<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\LocationPatch;
use AppBundle\Entity\StopPoint;
use AppBundle\Entity\User;

class SendReportCommandTest extends ApiTestCase
{
    private $user = null;
    private $day = 0;

    private function createLocationPatch(\stdClass $data)
    {
        $createdAt = date("Y-m-d",strtotime('-' . $this->day++ . ' day'));
        $stopPointEntity = new StopPoint();
        $locationPatchEntity = new LocationPatch();

        $stopPointEntity
            ->setName($data->stop_point->name)
            ->setRouteId($data->stop_point->route->id)
            ->setRouteName($data->stop_point->route->name)
            ->setCode($data->stop_point->code)
            ->setSourceName($data->stop_point->source->name)
        ;
        $this->getEntityManager()->persist($stopPointEntity);
        $locationPatchEntity
            ->setUser($this->user)
            ->setEquipmentId($stopPointEntity->getId())
            ->setCurrentLat($data->current_location->lat)
            ->setCurrentLon($data->current_location->lon)
            ->setReporterLat($data->reporter_location->location->lat)
            ->setReporterLon($data->reporter_location->location->lon)
            ->setReporterAccuracy($data->reporter_location->accuracy)
            ->setUsingReporterGeolocation(false)
            ->setCreatedAt(new \DateTime($createdAt))
        ;
        $this->getEntityManager()->persist($locationPatchEntity);
        $this->getEntityManager()->flush();
    }

    private function insertLocationPatches()
    {
        $rootDir = $this->getService('kernel')->getRootDir();
        $locationsPatches = json_decode(file_get_contents($rootDir.'/../tests/AppBundle/Controller/v1/data/SendReporterCommandTest/01_location_patches.json'));
        $this->user = $this->getEntityManager()->getRepository('AppBundle:User')->findOneByUsername('test');

        foreach ($locationsPatches as $locationsPatch) {
            $this->createLocationPatch($locationsPatch);
        }
        $this->assertDbCount(11, 'LocationPatch');
    }

    public function testFindByPeriodLocationPatches()
    {
        $this->insertLocationPatches();
        while ($this->day > 0)
        {
            $locationPatchRepository = $this->getEntityManager()->getRepository('AppBundle:LocationPatch');
            $locationsPatches = $locationPatchRepository->findByPeriod($this->day);
            $this->assertCount($this->day--, $locationsPatches);
        }
    }

    public function testCsvReportLocationPatches()
    {
        $this->insertLocationPatches();
        $rootDir = $this->getService('kernel')->getRootDir();
        $csvExpected = file_get_contents($rootDir.'/../tests/AppBundle/Controller/v1/data/SendReporterCommandTest/01_reporting.csv');
        $csv = $this->getService('AppBundle\Services\LocationPatch')->getCsvReportByPeriod(7);

        $this->assertEquals($csv, $csvExpected);
    }
}
