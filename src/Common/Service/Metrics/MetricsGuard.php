<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics;

use App\Common\Exception\Domain\DomainException;

class MetricsGuard
{
    public function __construct(
        private readonly string $appEnv,
        private readonly string $prometheusHost,
    ) {
    }

    public function guard(string $host): void
    {
        if ('dev' !== $this->appEnv) {
            if ($host !== $this->prometheusHost) {
                throw new DomainException('Invalid permissions');
            }
        }
    }
}
