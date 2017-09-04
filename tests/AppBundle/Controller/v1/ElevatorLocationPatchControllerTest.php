<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\Location;
use AppBundle\Entity\LocationPatch;

class ElevatorLocationPatchControllerTest extends ApiTestCase
{
    public function testPostLocationPatchesWithoutAuthorization()
    {
        $this->assertSecured('POST', 'patches/location/from_user_location');
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
            'reported_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $requiredFields = [
            'elevator.id',
            'elevator.name',
            'elevator.source',
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
                'id' => "ELEVATOR:42",
                'name' => 'ELEVATOR:42',
                'source' => []
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reported_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $requiredFields = [
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
                'id' => "ELEVATOR:42",
                'name' => 'ELEVATOR:42',
                'source' => ['name' => 'stiff'],
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reported_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $response = $this->client->request('POST', 'patches/location', ['body' => json_encode($data)]);
        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'Location');
        $location = $this->getEntityManager()->getRepository('AppBundle:Location')->findAll()[0];
        $this->assertValidPatch($data, $location);
    }

    private function assertValidPatch(array $data, Location $location)
    {
        $this->assertDbCount(1, 'Elevator');
        $elevator = $this->getEntityManager()->getRepository('AppBundle:Elevator')
            ->find($location->getEquipmentId());

        $this->assertEquals($data['elevator']['id'], $elevator->getCode());
        $this->assertEquals($data['elevator']['source']['name'], $elevator->getSourceName());
        $this->assertEquals($data['current_location']['lat'], $location->getCurrentLat());
        $this->assertEquals($data['current_location']['lon'], $location->getCurrentLon());
        $this->assertEquals($data['patched_location']['lat'], $location->getPatchedLat());
        $this->assertEquals($data['patched_location']['lon'], $location->getPatchedLon());
        $this->assertEquals($data['reported_location']['location']['lat'], $location->getReporterLat());
        $this->assertEquals($data['reported_location']['location']['lon'], $location->getReporterLon());
        $this->assertEquals($data['reported_location']['accuracy'], $location->getReporterAccuracy());
    }
}
