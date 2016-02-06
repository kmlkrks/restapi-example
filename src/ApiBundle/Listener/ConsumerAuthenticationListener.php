<?php

namespace ApiBundle\Listener;

use ApiBundle\Service\ConsumerService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class ConsumerAuthenticationListener
 * @package ApiBundle\Listener
 */
class ConsumerAuthenticationListener
{
    /**
     * @var \ApiBundle\Service\ConsumerService
     */
    private $consumerService;

    /**
     * @param ConsumerService $consumerService
     */
    public function __construct(ConsumerService $consumerService)
    {
        $this->consumerService = $consumerService;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $consumerKey = $event->getRequest()->headers->get('x-consumer-key');
        if (!$consumerKey || !$this->consumerService->checkConsumerKeyValid($consumerKey)) {
            throw new AuthenticationException('Invalid consumer key');
        }
    }
} 