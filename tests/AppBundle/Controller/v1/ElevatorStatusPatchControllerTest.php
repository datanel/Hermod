<?php

namespace Tests\AppBundle\Controller\v1;

class ElevatorStatusPatchControllerTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testIsSecured()
    {
        $this->assertSecured('POST', 'patches/status');
    }

    public function testCreateElevatorStatusWithoutRequiredData()
    {
        $requiredFields = [
            'type',
            'elevator',
            'currentStatus',
            'patchedStatus'
        ];
        $response = $this->client->request('POST', 'patches/status', ['body' => '{}']);
        $expectedErrors = array_map(
            function ($field) {
                return "Error at [$field]: This field is missing.";
            },
            $requiredFields
        );
        $this->assertError($response, 400, 'invalid_params', $expectedErrors);
    }

    public function testCreateElevatorStatus()
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
            'current_status' => 'unavailable',
            'patched_status' => 'available'
        ];
        $response = $this->client->request('POST', 'patches/status', ['body' => json_encode($data)]);
        $this->assertResourceCreated($response);
        $this->assertDbCount(1, 'StatusPatch');
        $this->client->request('POST', 'patches/status', ['body' => json_encode($data)]);
        $this->assertDbCount(2, 'StatusPatch');
    }
}
