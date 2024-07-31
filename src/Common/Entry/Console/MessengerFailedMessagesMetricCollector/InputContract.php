<?php

declare(strict_types=1);

namespace App\Common\Entry\Console\MessengerFailedMessagesMetricCollector;

use IWD\SymfonyEntryContract\Interfaces\InputContractInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class InputContract implements InputContractInterface
{
    /** User name */
    #[NotNull]
    #[NotBlank]
    #[Length(min: 1)]
    public string $queueName = 'failed';
}
