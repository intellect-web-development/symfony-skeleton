<?php

declare(strict_types=1);

namespace App\Common\Service\Metrics\Adapters\Redis;

class Config
{
    private string $host;
    private string $namespace;

    public function __construct(string $host, string $namespace)
    {
        $this->host = $host;
        $this->namespace = $namespace;
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
