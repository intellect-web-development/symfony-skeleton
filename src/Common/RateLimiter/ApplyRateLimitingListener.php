<?php

declare(strict_types=1);

namespace App\Common\RateLimiter;

use App\Auth\Infrastructure\Security\UserIdentityFetcher;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsEventListener(event: KernelEvents::RESPONSE, method: 'onRateLimit', priority: 80)]
readonly class ApplyRateLimitingListener
{
    public function __construct(
        private UserIdentityFetcher $userIdentityFetcher,
        /** @var RateLimiterFactory[] */
        private array $rateLimiterClassMap,
    ) {
    }

    public function onRateLimit(KernelEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        /** @var string $controllerClass */
        $controllerClass = $request->attributes->get('_controller');

        $routeName = $request->attributes->get('_route') ?? 'undefined';

        $rateLimiter = $this->rateLimiterClassMap[$controllerClass] ?? null;
        if (null === $rateLimiter) {
            return; // этому экшену не назначена служба ограничения количества запросов
        }

        $userIdentity = $this->userIdentityFetcher->tryFetch($request);

        //todo: сделать white-list по IP через env: $request->getClientIp()
        //todo: сделать механизм навешивания рейт-лимитов на отдельный экшен с гибкой настройкой

        if (null === $userIdentity) {
            $limit = $rateLimiter->create($routeName . ':' . $request->getClientIp())->consume();
        } else {
            $limit = $rateLimiter->create($routeName . ':' . $userIdentity->id)->consume();
        }

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
