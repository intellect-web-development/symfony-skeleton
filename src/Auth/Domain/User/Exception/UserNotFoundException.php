<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Exception;

use App\Common\Exception\Domain\DomainException;

final class UserNotFoundException extends DomainException
{
}
