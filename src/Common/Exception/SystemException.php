<?php

declare(strict_types=1);

namespace App\Common\Exception;

use Throwable;

class SystemException extends \Exception
{
    public function __construct(
        string $message = '',
        ?int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, (int) $code, $previous);
    }
}
