<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\LocationPatch;

class ElevatorLocationPatchControllerTest extends ApiTestCase
{
    public function testPostLocationPatchesWithoutAuthorization()
    {
        $this->assertSecured('POST', 'patches/location/from_reporter');
    }

    public function testPostLocationPatchesWithoutType()
    {
        $response = $this->client->request('POST', 'patches/location', ['body' => '{}']);
        $expectedErrorMessages = "Error at [type]: This field is missing.";

        $this->assertError($response, 400, 'bad_request', $expectedErrorMessages);
    }

    public function testPostLocationPatchesWithoutData()
    {
        $data = ['type' => 'elevator'];
        $requiredFields = [
            'elevator',
            'currentLocation',
            'patchedLocation',
            'reporterLocation'
        ];
        $response = $this->client->request('POST', 'patches/location', ['body' => json_encode($data)]);
        $expectedErrorMessages = array_map(
            function ($field) {
                return "Error at [$field]: This field is missing.";
            },
            $requiredFields
        );
        $this->assertError($response, 400, 'invalid_params', $expectedErrorMessages);
    }

    public function testPostLocationPatchesWithoutElevatorInformation()
    {
        $data = [
            'type' => 'elevator',
            'elevator' => [],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $requiredFields = [
            'elevator.name',
            'elevator.direction',
            'elevator.humanLocation',
            'elevator.station',
            'elevator.code',
            'elevator.source'
        ];
        $response = $this->client->request('POST', 'patches/location', ['body' => json_encode($data)]);
        $expectedErrorMessages = array_map(
            function ($field) {
                return "Error at [$field]: This field is missing.";
            },
            $requiredFields
        );
        $this->assertError($response, 400, 'invalid_params', $expectedErrorMessages);
    }

    public function testPostLocationPatchesWithoutElevatorSourceInformation()
    {
        $data = [
            'type' => 'elevator',
            'elevator' => [
                'name' => 'elevator_name',
                'direction' => 'elevator_direction',
                'human_location' => 'elevator_human_location',
                'station' => [],
                'code' => "4242",
                'source' => []
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $requiredFields = [
            'elevator.station.id',
            'elevator.station.name',
            'elevator.source.name'
        ];
        $response = $this->client->request('POST', 'patches/location', ['body' => json_encode($data)]);
        $expectedErrorMessages = array_map(
            function ($field) {
                return "Error at [$field]: This field is missing.";
            },
            $requiredFields
        );
        $this->assertError($response, 400, 'invalid_params', $expectedErrorMessages);
    }

    public function testPostLocationPatches()
    {
        $data = [
            'type' => 'elevator',
            'elevator' => [
                'name' => 'elevator_name',
                'direction' => 'elevator_direction',
                'human_location' => 'elevator_human_location',
                'station' => ['id' => 'station_id', 'name' => 'station_name'],
                'code' => "4242",
                'source' => ['name' => 'stiff']
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $response = $this->client->request('POST', 'patches/location', ['body' => json_encode($data)]);
        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'LocationPatch');
        $location = $this->getEntityManager()->getRepository('AppBundle:LocationPatch')->findAll()[0];
        $this->assertValidPatch($data, $location);
    }

    private function assertValidPatch(array $data, LocationPatch $location)
    {
        $this->assertDbCount(1, 'Elevator');
        $elevator = $this->getEntityManager()->getRepository('AppBundle:Elevator')
            ->find($location->getEquipmentId());

        $this->assertEquals($data['elevator']['name'], $elevator->getName());
        $this->assertEquals($data['elevator']['direction'], $elevator->getDirection());
        $this->assertEquals($data['elevator']['human_location'], $elevator->getHumanLocation());
        $this->assertEquals($data['elevator']['station']['id'], $elevator->getStationId());
        $this->assertEquals($data['elevator']['station']['name'], $elevator->getStationName());
        $this->assertEquals($data['elevator']['code'], $elevator->getCode());
        $this->assertEquals($data['elevator']['source']['name'], $elevator->getSourceName());
        $this->assertEquals($data['current_location']['lat'], $location->getCurrentLat());
        $this->assertEquals($data['current_location']['lon'], $location->getCurrentLon());
        $this->assertEquals($data['patched_location']['lat'], $location->getPatchedLat());
        $this->assertEquals($data['patched_location']['lon'], $location->getPatchedLon());
        $this->assertEquals($data['reporter_location']['location']['lat'], $location->getReporterLat());
        $this->assertEquals($data['reporter_location']['location']['lon'], $location->getReporterLon());
        $this->assertEquals($data['reporter_location']['accuracy'], $location->getReporterAccuracy());
    }
}
