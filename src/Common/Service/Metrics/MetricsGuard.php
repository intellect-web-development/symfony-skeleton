<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics;

use App\Common\Exception\Domain\DomainException;

class MetricsGuard
{
    private string $prometheusHost;
    private string $appEnv;

    public function __construct(string $appEnv, string $prometheusHost)
    {
        $this->prometheusHost = $prometheusHost;
        $this->appEnv = $appEnv;
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
