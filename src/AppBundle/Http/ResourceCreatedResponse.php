<?php

namespace AppBundle\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResourceCreatedResponse extends JsonResponse
{
    /**
     * @param mixed $data The response data
     * @param array $headers An array of response headers
     */
    public function __construct($data = null, $headers = array())
    {
        parent::__construct($data, 201, $headers);
    }
}
