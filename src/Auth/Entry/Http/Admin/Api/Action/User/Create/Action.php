<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Api\Action\User\Create;

use App\Auth\Application\User\UseCase\Create\Handler;
use App\Auth\Entry\Http\Admin\Api\Contract\User\CommonOutputContract;
use App\Common\Exception\Domain\DomainException;
use IWD\SymfonyEntryContract\Dto\Input\OutputFormat;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Action
{
    public const NAME = 'api_admin_app_auth_user_create';

    #[OA\Tag(name: 'Auth.User')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            description: 'Create command for User',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    ref: new Model(type: InputContract::class),
                ),
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'User success created',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'status',
                    type: 'integer',
                    example: 201
                ),
                new OA\Property(
                    property: 'ok',
                    type: 'boolean',
                    example: true
                ),
                new OA\Property(
                    property: 'data',
                    ref: new Model(type: CommonOutputContract::class)
                ),
                new OA\Property(
                    property: 'messages',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                    )
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request',
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
    )]
    #[Route(
        path: '/api/admin/auth/users/create.{_format}',
        name: self::NAME,
        defaults: ['_format' => 'json'],
        methods: ['POST'],
    )]
    public function action(
        InputContract $contract,
        CommandFactory $commandFactory,
        Handler $handler,
        OutputFormat $outputFormat,
        ResponsePresenter $responsePresenter,
        MetricSender $metricSender,
    ): Response {
        $command = $commandFactory->create($contract);
        try {
            $result = $handler->handle($command);
        } catch (DomainException $domainException) {
        } finally {
            $result ??= null;
            $domainException ??= null;
        }

        $metricSender->send($result, $domainException);

        return $responsePresenter->present($result, $domainException, $outputFormat);
    }
}
