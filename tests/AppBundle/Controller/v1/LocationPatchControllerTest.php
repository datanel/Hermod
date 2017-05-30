<?php

namespace Tests\AppBundle\Controller\v1;

use Tests\AppBundle\Controller\AbstractControllerTest;

class LocationPatchControllerTest extends AbstractControllerTest
{
    public function testPostLocationPatches()
    {
        $data = [
            'source' => ['name' => 'stif'],
            'stop_point' => ['id' => 'some_sp_id', 'name' => 'some_sp_name'],
            'stop_point_current_location' => ['lon' => 1, 'lat' => 2],
            'route' => ['id' => 'some_id', 'name' => 'some_name'],
            'stop_point_patched_location' => ['lat' => 4, 'lon' => 3]
        ];

        $request = $this->client->request('POST', '/v1/location_patches', ['body' => json_encode($data)]);

        $this->assertEquals(201, $request->getStatusCode());
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

        $request = $this->client->request(
            'POST',
            '/v1/location_patches/from_user_location',
            ['body' => json_encode($data)]
        );

        $this->assertEquals(201, $request->getStatusCode());
    }
}
