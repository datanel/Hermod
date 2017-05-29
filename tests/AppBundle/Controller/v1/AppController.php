<?php

namespace Tests\AppBundle\Controller\v1;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class AppControllerTest extends TestCase
{
    private $client = null;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->client = new Client();
        $this->client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8080',
                'http_errors' => false
            ]
        );
    }

    public function testStatus()
    {
        $request = $this->client->get('/v1/status');

        $this->assertEquals(200, $request->getStatusCode());
        $data = json_decode($request->getBody(true), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals($data['status'], 'OK');
    }
}
