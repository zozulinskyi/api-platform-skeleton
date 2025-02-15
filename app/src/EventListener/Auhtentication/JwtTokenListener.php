<?php
declare(strict_types=1);

namespace App\EventListener\Auhtentication;

use App\Repository\Authentication\SessionRepository;
use Carbon\Carbon;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEventListener(event: Events::JWT_CREATED, method: 'onJwtCreated')]
#[AsEventListener(event: Events::JWT_DECODED, method: 'onJwtDecoded')]
#[AsEventListener(event: Events::JWT_AUTHENTICATED, method: 'onJwtAuthenticated')]
final readonly class JwtTokenListener
{
    private const string SESSION_KEY = 'sessionId';

    public function __construct(
        private RequestStack $requestStack,
        private SessionRepository $sessionRepository,
    )
    {}

    public function onJwtCreated(JWTCreatedEvent $event): void
    {
        $session = $this->sessionRepository->generate(
            user: $event->getUser(),
            request: $this->requestStack->getCurrentRequest(),
        );

        $event->setData(
            data: array_merge(
                $event->getData(),
                [
                    'exp' => Carbon::now()->addYear()->getTimestamp(), // generate token for 1 year
                    self::SESSION_KEY => $session->getId()->toString(), // store session for possibility to can revoke this token
                ],
            ),
        );
    }

    public function onJwtDecoded(JWTDecodedEvent $event): void
    {
        $isValidSession = array_key_exists(key: self::SESSION_KEY, array: $event->getPayload())
            && $this->sessionRepository->exists(sessionId: $event->getPayload()[self::SESSION_KEY]);

        if ($isValidSession === false) {
            $event->markAsInvalid();
        }
    }

    public function onJwtAuthenticated(JWTAuthenticatedEvent $event): void
    {
        $sessionId = (string)$event->getPayload()[self::SESSION_KEY];

        $event->getToken()->setAttribute(name: self::SESSION_KEY, value: $sessionId);
        $this->sessionRepository->update(request: $this->requestStack->getCurrentRequest(), sessionId: $sessionId);
    }
}
