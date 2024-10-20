<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Api\Action\User\Create;

use App\Auth\Application\User\UseCase\Create\Result;
use App\Auth\Domain\User\Exception\UserEmailAlreadyTakenException;
use App\Auth\Entry\Http\Admin\Api\Contract\User\CommonOutputContract;
use App\Common\Exception\Domain\DomainException;
use IWD\SymfonyEntryContract\Dto\Input\OutputFormat;
use IWD\SymfonyEntryContract\Dto\Output\ApiFormatter;
use IWD\SymfonyEntryContract\Service\Presenter;
use Symfony\Component\HttpFoundation\Response;

readonly class ResponsePresenter
{
    public function __construct(private Presenter $presenter)
    {
    }

    public function present(
        ?Result $result,
        ?DomainException $exception,
        OutputFormat $outputFormat,
    ): Response {
        if (null !== $result) {
            return $this->presenter->present(
                data: ApiFormatter::prepare(
                    data: CommonOutputContract::create($result->user),
                    messages: ['Success']
                ),
                outputFormat: $outputFormat,
                status: Response::HTTP_CREATED,
            );
        }
        if ($exception instanceof UserEmailAlreadyTakenException) {
            return $this->presenter->present(
                data: ApiFormatter::prepare(
                    data: null,
                    messages: ['Email already taken']
                ),
                outputFormat: $outputFormat,
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        throw new DomainException('Unexpected termination of the script');
    }
}
