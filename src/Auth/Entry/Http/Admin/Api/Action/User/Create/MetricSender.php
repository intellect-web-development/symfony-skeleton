<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Api\Action\User\Create;

use App\Auth\Application\User\UseCase\Create\Result;
use App\Auth\Domain\User\Exception\UserEmailAlreadyTakenException;
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
        if ($exception instanceof UserEmailAlreadyTakenException) {
            $this->metrics->createCounter(
                name: $metricPrefix . ':email_already_taken',
                help: 'Email already taken'
            )->inc();
        }
    }
}
