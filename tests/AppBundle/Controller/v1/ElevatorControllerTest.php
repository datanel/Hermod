<?php

namespace Tests\AppBundle\Controller\v1;

class ElevatorControllerTest extends ApiTestCase
{
    public function testNumberStatusPatchesOfElevator()
    {
        $rootDir = $this->getService('kernel')->getRootDir();
        $requests = json_decode(file_get_contents($rootDir.'/../tests/AppBundle/Controller/v1/data/ElevatorControllerTest/01_requests.json'));

        foreach ($requests as $request) {
            $response = $this->client->request('POST', 'patches/status', ['body' => json_encode($request)]);
            $this->assertResourceCreated($response);
        }
        $this->assertDbCount(10, 'StatusPatch');
        $response = $this->client->request('GET', 'elevators/');
        $result = $this->getBody($response);
        $this->assertEquals(5, count($result[0]['status_patches']));
    }

}
