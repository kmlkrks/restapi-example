<?php

namespace ApiBundle\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class ExceptionListener
 * @package ApiBundle\Listener
 */
class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } elseif ($exception instanceof AuthenticationException) {
            $response->setStatusCode(403);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setData(
            array(
                'message' => $exception->getMessage(),
                'status_code' => $response->getStatusCode(),
                'trace' => $exception->getTrace(),
                'code' => $exception->getCode()
            )
        );

        $event->setResponse($response);
    }
} 