<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController extends BaseController
{
    /**
     * @Route("/status", name="status")
     * @Method("GET")
     */
    public function statusAction()
    {
        return new JsonResponse([
            'status' => 'OK'
        ]);
    }
}
