<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\Location;
use AppBundle\Entity\LocationPatch;

class StopPointLocationPatchControllerTest extends ApiTestCase
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
        $data = ['type' => 'stop_point'];
        $requiredFields = [
            'stopPoint',
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

    public function testPostLocationPatchesWithoutStopPointInformation()
    {
        $data = [
            'type' => 'stop_point',
            'stop_point' => [],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $requiredFields = [
            'stopPoint.code',
            'stopPoint.name',
            'stopPoint.source',
            'stopPoint.route'
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

    public function testPostLocationPatchesWithoutStopPointRouteAndSourceInformation()
    {
        $data = [
            'type' => 'stop_point',
            'stop_point' => [
                'code' => 42,
                'name' => 'STOP_POINT:42',
                'route' => [],
                'source' => []
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $requiredFields = [
            'stopPoint.source.name',
            'stopPoint.route.id',
            'stopPoint.route.name'
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
            'type' => 'stop_point',
            'stop_point' => [
                'code' => 42,
                'name' => 'STOP_POINT:42',
                'route' => ['id' => 'string', 'name' => 'string'],
                'source' => ['name' => 'navitia2'],
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $response = $this->client->request('POST', 'patches/location', ['body' => json_encode($data)]);

        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'Location');
        $location = $this->getEntityManager()->getRepository('AppBundle:Location')->findAll()[0];
        $this->assertValidPatch($data, $location);
    }

    public function testPostLocationPatchesFromUserLocation()
    {
        $data = [
            'type' => 'stop_point',
            'stop_point' => [
                'code' => 42,
                'name' => 'STOP_POINT:42',
                'route' => ['id' => 'string', 'name' => 'string'],
                'source' => ['name' => 'navitia2']
            ],
            'current_location' => ['lat' => 0, 'lon' => 0],
            'patched_location' => ['lat' => 42, 'lon' => 21],
            'reporter_location' => ['location' => ['lat' => 42, 'lon' => 21], 'accuracy' => 5]
        ];
        $response = $this->client->request(
            'POST',
            'patches/location/from_user_location',
            ['body' => json_encode($data)]
        );

        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'Location');
        $location = $this->getEntityManager()->getRepository('AppBundle:Location')->findAll()[0];
        $this->assertValidPatch($data, $location);
    }

    private function assertValidPatch(array $data, Location $location)
    {
        $this->assertDbCount(1, 'StopPoint');
        $stopPoint = $this->getEntityManager()->getRepository('AppBundle:StopPoint')
            ->find($location->getEquipmentId());

        $this->assertEquals($data['stop_point']['source']['name'], $stopPoint->getSourceName());
        $this->assertEquals($data['stop_point']['route']['id'], $stopPoint->getRouteId());
        $this->assertEquals($data['stop_point']['route']['name'], $stopPoint->getRouteName());
        $this->assertEquals($data['stop_point']['code'], $stopPoint->getCode());
        $this->assertEquals($data['stop_point']['name'], $stopPoint->getName());
        $this->assertEquals($data['current_location']['lat'], $location->getCurrentLat());
        $this->assertEquals($data['current_location']['lon'], $location->getCurrentLon());
        $this->assertEquals($data['patched_location']['lat'], $location->getPatchedLat());
        $this->assertEquals($data['patched_location']['lon'], $location->getPatchedLon());
        $this->assertEquals($data['reporter_location']['location']['lat'], $location->getReporterLat());
        $this->assertEquals($data['reporter_location']['location']['lon'], $location->getReporterLon());
        $this->assertEquals($data['reporter_location']['accuracy'], $location->getReporterAccuracy());
    }
}
