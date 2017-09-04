<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Elevator;

class LoadElevatorData implements FixtureInterface
{
    private $elevators = [
        [
            'code' => 'ELEVATOR:42',
            'source_name' => 'stiff',
            'station_id' => 'STATION_ID',
            'station_name' => 'STATION_NAME',
            'station_name' => 'HUMAN_LOCATION',
            'direction' => 'DIRECTION',
            'human_location' => 'HUMAN_LOCATION'
        ]
    ];

    private function createEquipment(array $data)
    {
        $elevator = new Elevator();

        $elevator
            ->setCode($data['code'])
            ->setSourceName($data['source_name'])
            ->setStationId($data['station_id'])
            ->setStationName($data['station_name'])
            ->setDirection($data['direction'])
            ->setHumanLocation($data['human_location'])
        ;
        return $elevator;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->elevators as $elevator) {
            $manager->persist($this->createEquipment($elevator));
        }
        $manager->flush();
    }
}