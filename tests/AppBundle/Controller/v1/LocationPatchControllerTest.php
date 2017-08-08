<?php

namespace Tests\AppBundle\Controller\v1;

use AppBundle\Entity\LocationPatch;

class LocationPatchControllerTest extends ApiTestCase
{
    public function testPostLocationPatchesWithoutAuthorization()
    {
        $this->assertSecured('POST', 'location_patches/from_user_location');
    }

    public function testPostLocationPatchesWithoutData()
    {
        $requiredFields = [
            'source',
            'stop_point',
            'stop_point_current_location',
            'route',
            'stop_point_patched_location'
        ];
        $response = $this->client->request('POST', 'location_patches', ['body' => '{}']);
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
            'source' => ['name' => 'stif'],
            'stop_point' => ['id' => 'some_sp_id', 'name' => 'some_sp_name'],
            'stop_point_current_location' => ['lat' => 1, 'lon' => 2],
            'route' => ['id' => 'some_id', 'name' => 'some_name'],
            'stop_point_patched_location' => ['lat' => 4, 'lon' => 3]
        ];

        $response = $this->client->request('POST', 'location_patches', ['body' => json_encode($data)]);

        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'LocationPatch');
        /** @var LocationPatch $locationPatch */
        $locationPatch = $this->getEntityManager()->getRepository('AppBundle:LocationPatch')->findAll()[0];
        $this->assertValidPatch($data, $locationPatch);
    }

    public function testPostLocationPatchesFromUserLocation()
    {
        $data = [
            'source' => ['name' => 'stif'],
            'stop_point' => ['id' => 'some_sp_id', 'name' => 'some_sp_name'],
            'stop_point_current_location' => ['lon' => 1, 'lat' => 2],
            'route' => ['id' => 'some_id', 'name' => 'some_name'],
            'gps' => ['location' => ['lat' => 4, 'lon' => 3], 'accuracy' => 10.446549465]
        ];

        $response = $this->client->request(
            'POST',
            'location_patches/from_user_location',
            ['body' => json_encode($data)]
        );

        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'LocationPatch');
        $locationPatch = $this->getEntityManager()->getRepository('AppBundle:LocationPatch')->findAll()[0];
        $this->assertValidPatch($data, $locationPatch);
    }

    private function assertValidPatch(array $data, LocationPatch $patch)
    {
        $this->assertEquals($data['source']['name'], $patch->getSourceName());
        $this->assertEquals($data['route']['id'], $patch->getRouteId());
        $this->assertEquals($data['route']['name'], $patch->getRouteName());
        $this->assertEquals($data['stop_point']['id'], $patch->getStopPointId());
        $this->assertEquals($data['stop_point']['name'], $patch->getStopPointName());
        $this->assertEquals($data['stop_point_current_location']['lat'], $patch->getStopPointCurrentLat());
        $this->assertEquals($data['stop_point_current_location']['lon'], $patch->getStopPointCurrentLon());

        if (isset($data['gps'])) {
            $this->assertEquals($data['gps']['location']['lat'], $patch->getUserGeolocationLat());
            $this->assertEquals($data['gps']['location']['lon'], $patch->getUserGeolocationLon());
        }

        // if the used did not provided a sp patched location, it means that it is patched using a gps location
        if (isset($data['stop_point_patched_location'])) {
            $this->assertEquals($data['stop_point_patched_location']['lat'], $patch->getStopPointPatchedLat());
            $this->assertEquals($data['stop_point_patched_location']['lon'], $patch->getStopPointPatchedLon());
        } else {
            $this->assertEquals($data['gps']['location']['lat'], $patch->getStopPointPatchedLat());
            $this->assertEquals($data['gps']['location']['lon'], $patch->getStopPointPatchedLon());
        }
    }
}
