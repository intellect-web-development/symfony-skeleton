<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Token\Authentication;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use IWD\Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Email]
    #[Length(max: 255)]
    public string $email;

    #[NotNull]
    public string $password;
}
