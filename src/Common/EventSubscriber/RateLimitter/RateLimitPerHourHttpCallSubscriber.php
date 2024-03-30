<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\RateLimitter;

use App\Auth\Infrastructure\Security\UserIdentityFetcher;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsEventListener(event: KernelEvents::RESPONSE, method: 'onRateLimit', priority: 90)]
readonly class RateLimitPerHourHttpCallSubscriber
{
    public function __construct(
        private RateLimiterFactory $anonymousApiCommon,
        private RateLimiterFactory $authenticatedApiCommon,
        private UserIdentityFetcher $userIdentityFetcher,
    ) {
    }

//     todo: это заготовка, позже необходимо будет произвести более тонкую настройку рейт-лимитов. Например, смотреть, если
//      пользователь не авторизован - то работать по $anonymousApi и getClientIp, если авторизован, то по $user->getId и $authenticatedApi
    public function onRateLimit(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $userIdentity = $this->userIdentityFetcher->tryFetch($request);

        //todo: сделать white-list по IP через env: $request->getClientIp()

        if (null === $userIdentity) {
            $limiter = $this->anonymousApiCommon->create($request->getClientIp());
        } else {
            $limiter = $this->authenticatedApiCommon->create($userIdentity->id);
        }

        $limit = $limiter->consume();

        if (false === $limit->isAccepted()) {
            throw new HttpException(
                statusCode: Response::HTTP_TOO_MANY_REQUESTS,
                message: 'Too many requests',
                headers: [
                    'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
                    'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp() - time(),
                    'X-RateLimit-Limit' => $limit->getLimit(),
                ]
            );
        }
    }
}
