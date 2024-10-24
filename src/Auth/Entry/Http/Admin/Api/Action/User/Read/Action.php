<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Api\Action\User\Read;

use App\Auth\Domain\User\User;
use App\Auth\Entry\Http\Admin\Api\Contract\User\CommonOutputContract;
use IWD\SymfonyDoctrineSearch\Service\QueryBus\Aggregate\Bus;
use IWD\SymfonyDoctrineSearch\Service\QueryBus\Aggregate\Query;
use IWD\SymfonyEntryContract\Dto\Input\OutputFormat;
use IWD\SymfonyEntryContract\Dto\Output\ApiFormatter;
use IWD\SymfonyEntryContract\Service\Presenter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Action
{
    public const string NAME = 'api_admin_app_auth_user_read';

    #[OA\Tag(name: 'Auth.User')]
    #[OA\Response(
        response: 200,
        description: 'User read',
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
        path: '/api/admin/auth/users/{id}.{_format}',
        name: self::NAME,
        defaults: ['_format' => 'json'],
        methods: ['GET'],
    )]
    public function action(
        string $id,
        Bus $bus,
        OutputFormat $outputFormat,
        Presenter $presenter,
    ): Response {
        $query = new Query(
            aggregateId: $id,
            targetEntityClass: User::class
        );

        /** @var User $user */
        $user = $bus->query($query);

        return $presenter->present(
            data: ApiFormatter::prepare(
                CommonOutputContract::create($user)
            ),
            outputFormat: $outputFormat
        );
    }
}
