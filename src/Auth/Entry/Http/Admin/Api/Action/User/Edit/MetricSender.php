<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Api\Action\User\Edit;

use App\Auth\Application\User\UseCase\Edit\Result;
use App\Auth\Domain\User\Exception\UserNotFoundException;
use App\Common\Exception\Domain\DomainException;
use App\Common\Service\Metrics\AdapterInterface;

/** @codeCoverageIgnore */
readonly class MetricSender
{
    public function __construct(private AdapterInterface $metrics)
    {
    }

    public function send(
        ?Result $result,
        ?DomainException $exception,
    ): void {
        $metricPrefix = str_replace('.', '_', Action::NAME);

        if (null !== $result) {
            $this->metrics->createCounter(
                name: $metricPrefix . ':success',
                help: 'Success'
            )->inc();
        }
        if ($exception instanceof UserNotFoundException) {
            $this->metrics->createCounter(
                name: $metricPrefix . ':user_not_found',
                help: 'User not found'
            )->inc();
        }
    }
}
