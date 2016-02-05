<?php

namespace ApiBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ResponseListener
 * @package ApiBundle\Listener
 * @author Kemal KARAKAŞ <kmlkarakas@gmail.com>
 */
class ResponseListener
{
    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        switch ($method) {
            case 'POST':
                $statusCode = 201;
                break;
            case 'DELETE':
                $statusCode = 204;
                break;
            default:
                $statusCode = 200;
                break;
        }

        $event->setResponse(new JsonResponse($result, $statusCode));
    }
} 