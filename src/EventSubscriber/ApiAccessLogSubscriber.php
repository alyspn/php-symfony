<?php


namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiAccessLogSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $tokenStorage;

    public function __construct(LoggerInterface $logger, TokenStorageInterface $tokenStorage)
    {
        $this->logger = $logger;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (str_starts_with($request->getPathInfo(), '/api')) {
            $token = $this->tokenStorage->getToken();
            $user = $token ? $token->getUser() : null;
            $username = $user && is_object($user) ? $user->getFirstName() : 'anonymous';

            $this->logger->info(sprintf('API Access: %s %s by %s', 
                $request->getMethod(), 
                $request->getRequestUri(), 
                $username));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
