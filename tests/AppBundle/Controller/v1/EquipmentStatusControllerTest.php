<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\Equipment;
use AppBundle\Entity\EquipmentStatus;

class EquipmentStatusControllerTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->createEquipment();
    }

    private function createEquipment()
    {
        $equipment = (new Equipment(42))
            ->setCode('a_code')
            ->setDirection('a_direction')
            ->setLocation('a_location')
            ->setStationName('a_station')
            ->setStationId('an_id')
            ->setType('elevator');

        $this->getEntityManager()->persist($equipment);
        $this->getEntityManager()->flush();

        return $equipment;
    }

    public function testIsSecured()
    {
        $this->assertSecured('POST', 'equipment_status');
    }

    public function testCreateEquipmentStatusWithoutRequiredData()
    {
        $requiredFields = [
            'source',
            'type',
            'equipment',
            'status'
        ];
        $response = $this->client->request('POST', 'equipment_status', ['body' => '{}']);
        $expectedErrors = array_map(
            function ($field) {
                return "Error at [$field]: This field is missing.";
            },
            $requiredFields
        );
        $this->assertError($response, 400, 'invalid_params', $expectedErrors);
    }

    public function testCreateEquipmentStatusWithUnknownEquipment()
    {
        $data = [
            'source' => ['name' => 'a_source'],
            'type' => 'traveler',
            'equipment' => ['id' => 'unknown_id', 'type' => 'elevator'],
            'status' => 'OK'
        ];
        $response = $this->client->request('POST', 'equipment_status', ['body' => json_encode($data)]);

        $this->assertError(
            $response,
            400,
            'invalid_params',
            ['Error at [equipment]: Unable to find any equipment of type \'elevator\' with the id \'unknown_id\'']
        );
    }

    public function testCreateEquipmentStatus()
    {
        $data = [
            'source' => ['name' => 'a_source'],
            'type' => 'traveler',
            'equipment' => ['id' => 42, 'type' => 'elevator'],
            'status' => 'OK'
        ];
        $response = $this->client->request('POST', 'equipment_status', ['body' => json_encode($data)]);
        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'EquipmentStatus');
        $this->client->request('POST', 'equipment_status', ['body' => json_encode($data)]);
        $this->assertDbCount(2, 'EquipmentStatus');
        $statuses = $this->getEntityManager()->getRepository('AppBundle:EquipmentStatus')->findAll();

        foreach ($statuses as $status) {
            $this->assertValidEquipmentStatus($data, $status);
        }
    }

    private function assertValidEquipmentStatus(array $data, EquipmentStatus $equipmentStatus)
    {
        $this->assertEquals($data['source']['name'], $equipmentStatus->getSourceName());
        $this->assertEquals($data['type'], $equipmentStatus->getType());
        $this->assertEquals($data['equipment']['id'], $equipmentStatus->getEquipment()->getId());
        $this->assertEquals($data['status'], $equipmentStatus->getReportedStatus());
    }
}
