<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics\Adapters\Redis;

use App\Common\Service\Metrics\AdapterInterface;
use Prometheus\Counter;

class Adapter implements AdapterInterface
{
    private \Prometheus\Storage\Redis $adapter;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->adapter = new \Prometheus\Storage\Redis([
            'host' => $config->getHost(),
        ]);
        $this->config = $config;
    }

    public function collect(): array
    {
        return $this->adapter->collect();
    }

    public function createCounter(
        string $name,
        string $help = '',
        array $labels = []
    ): Counter {
        return new Counter(
            $this->adapter,
            $this->config->getNamespace(),
            $name,
            $help,
            $labels
        );
    }
}
