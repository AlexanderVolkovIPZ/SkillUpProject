<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class JWTEventListener
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User) {
            $payload = $event->getData();
            $payload['id'] = $user->getId();

            $event->setData($payload);
        }
    }
}
