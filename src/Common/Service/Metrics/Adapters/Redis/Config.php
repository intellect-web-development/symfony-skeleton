<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics\Adapters\Redis;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class Config
{
    public function __construct(
        #[Autowire('%env(METRICS_SIDECAR_HOST)%')]
        private string $host,
        #[Autowire('%env(METRICS_NAMESPACE)%')]
        private string $namespace,
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
