<?php

namespace Tests\AppBundle\Controller\v1;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\DataFixtures\ORM\LoadUserData;

abstract class ApiTestCase extends KernelTestCase
{
    protected $client = null;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8080/v1/',
                'http_errors' => false,
                'headers' => ['Authorization' => 'a2540dc6-5b0b-45b9-8a7d-8c6fcf03e1df']
            ]
        );
    }

    public static function setUpBeforeClass()
    {
        self::bootKernel();
    }

    public function setUp()
    {
        parent::setUp();

        $this->purgeDatabase();

        $fixture = new LoadUserData();
        $fixture->load($this->getEntityManager());
    }

    private function purgeDatabase()
    {
        (new ORMPurger($this->getEntityManager()))->purge();
    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()->get($id);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    protected function getBody(ResponseInterface $response)
    {
        return \json_decode($response->getBody(), true);
    }

    protected function assertSecured($method, $path)
    {
        $request = $this->client->request($method, $path, ['headers' => ['Authorization' => null]]);
        $this->assertEquals(401, $request->getStatusCode());
        $request = $this->client->request($method, $path, ['headers' => ['Authorization' => 'wrong_token']]);
        $this->assertEquals(401, $request->getStatusCode());
    }

    protected function assertError(ResponseInterface $response, $statusCode, $errorType, $errorMessages)
    {
        $this->assertEquals($statusCode, $response->getStatusCode());
        $responseBody = $this->getBody($response);
        $this->assertArrayHasKey('error', $responseBody);
        $this->assertEquals($responseBody['error'], $errorType);
        $this->assertArrayHasKey('messages', $responseBody);

        $errorMessages = (array)$errorMessages;
        $this->assertEquals(count($errorMessages), count($responseBody['messages']));
        $this->assertEquals($errorMessages, $responseBody['messages']);
    }

    protected function assertResourceCreated(ResponseInterface $response)
    {
        $this->assertEquals(201, $response->getStatusCode());
    }

    protected function assertDbCount($expected, string $entityName)
    {
        $count = $this->getEntityManager()->createQuery(
            sprintf('SELECT COUNT(e) FROM AppBundle:%s e', $entityName)
        )->getSingleScalarResult();
        $this->assertEquals($expected, $count, "$count records of $entityName found in DB, $expected expected");
    }

    protected function assertStatus(ResponseInterface $response, $status)
    {
        $this->assertEquals($response->getStatusCode(), $status);
    }

    protected function tearDown()
    {
        // does not call parent tearDown to avoid shutting down the kernel every time
    }
}
