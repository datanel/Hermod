<?php

namespace Tests\AppBundle\Controller\v1;

use Tests\AppBundle\Controller\AbstractControllerTest;

class AppControllerTest extends AbstractControllerTest
{
    public function testStatus()
    {
        $request = $this->client->get('/v1/status');

        $this->assertEquals(200, $request->getStatusCode());
        $data = json_decode($request->getBody(true), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals($data['status'], 'OK');
    }
}
