<?php

namespace Tests\AppBundle\Controller\v1;

class AppControllerTest extends ApiTestCase
{
    public function testStatus()
    {
        $response = $this->client->get('status');

        $this->assertEquals(200, $response->getStatusCode());
        $data = $this->getBody($response);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals($data['status'], 'OK');
    }
}
