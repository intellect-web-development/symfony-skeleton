<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Token\InvalidateAllRefreshTokensExceptCurrent;

use Symfony\Component\Validator\Constraints\NotNull;
use IWD\Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    public string $refreshToken;
}
