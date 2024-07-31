<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics\Adapters\Redis;

use App\Common\Service\Metrics\AdapterInterface;
use Prometheus\Counter;
use Prometheus\Gauge;

readonly class Adapter implements AdapterInterface
{
    private \Prometheus\Storage\Redis $adapter;

    public function __construct(
        private Config $config
    ) {
        $this->adapter = new \Prometheus\Storage\Redis([
            'host' => $config->host,
        ]);
    }

    public function collect(): array
    {
        return $this->adapter->collect();
    }

    public function createCounter(
        string $name,
        string $help = '',
        array $labels = [],
    ): Counter {
        return new Counter(
            storageAdapter: $this->adapter,
            namespace: $this->config->namespace,
            name: $name,
            help: $help,
            labels: $labels,
        );
    }

    public function createGauge(
        string $name,
        string $help = '',
        array $labels = [],
    ): Gauge {
        return new Gauge(
            storageAdapter: $this->adapter,
            namespace: $this->config->namespace,
            name: $name,
            help: $help,
            labels: $labels,
        );
    }
}
