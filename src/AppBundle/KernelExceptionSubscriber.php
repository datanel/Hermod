<?php

namespace AppBundle;

use AppBundle\Http\Exception\HttpErrorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribe for HTTP error Exceptions and turn them into nicely formatted errors
 */
class KernelExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof HttpErrorException) {
            return;
        }

        $event->setResponse(new JsonResponse(
            $exception,
            $exception->getHttpStatusCode()
        ));
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => ['onKernelException']];
    }
}
