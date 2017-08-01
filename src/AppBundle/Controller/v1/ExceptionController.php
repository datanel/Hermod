<?php

namespace AppBundle\Controller\v1;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Extends the standard twig bundle ExceptionController to serve json on Exception (eg: 404) instead of HTML
 * We still serve HTML if we are in a dev env to ease debugging
 * @see http://symfony.com/doc/current/controller/error_pages.html#overriding-the-default-exceptioncontroller
 */
class ExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController
{
    protected $env;

    public function __construct(\Twig_Environment $twig, $debug, $env)
    {
        $this->env = $env;
        parent::__construct($twig, $debug);
    }

    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        $request->setRequestFormat($this->getRequestedFormat($request));

        return parent::showAction($request, $exception, $logger);
    }

    /**
     * Returns the preferable response format for the given request
     * We always want to return json except when we are in a dev environment and the caller
     * didn't specify a 'application/json' Content-Type header (most probably mean that's a dev on a browser)
     *
     * @param Request $request
     * @return string html|json
     */
    protected function getRequestedFormat(Request $request)
    {
        // we want a html response in dev when json is not request (to have readable exception)
        if ($this->env == 'dev' && false === strpos($request->headers->get('Content-Type'), 'application/json')) {
            return 'html';
        }
        return 'json';
    }
}
