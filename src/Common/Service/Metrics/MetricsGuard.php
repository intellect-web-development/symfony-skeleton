<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics;

use App\Common\Exception\Domain\DomainException;

class MetricsGuard
{
    public function __construct(
        private readonly string $prometheusToken,
    ) {
    }

    public function guard(string $token): void
    {
        if ($token !== $this->prometheusToken) {
            throw new DomainException('Invalid permissions');
        }
    }
}
