<?php

namespace Tests\AppBundle\Controller;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class AbstractControllerTest extends TestCase
{
    protected $client = null;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8080',
                'http_errors' => false
            ]
        );
    }
}
