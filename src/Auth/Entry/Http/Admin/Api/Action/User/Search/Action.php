<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Api\Action\User\Search;

use App\Auth\Domain\User\User;
use App\Auth\Entry\Http\Admin\Api\Contract\User\CommonOutputContract;
use IWD\SymfonyDoctrineSearch\Dto\Input\SearchQuery;
use IWD\SymfonyDoctrineSearch\Dto\Output\OutputPagination;
use IWD\SymfonyDoctrineSearch\Service\QueryBus\Search\Bus;
use IWD\SymfonyDoctrineSearch\Service\QueryBus\Search\Query;
use IWD\SymfonyEntryContract\Dto\Input\OutputFormat;
use IWD\SymfonyEntryContract\Dto\Output\ApiFormatter;
use IWD\SymfonyEntryContract\Service\Presenter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Action
{
    public const string NAME = 'api_admin_app_auth_user_search';

    #[OA\Tag(name: 'Auth.User')]
    #[OA\Get(
        parameters: [
            new OA\Parameter(
                name: 'searchQuery',
                in: 'query',
                required: false,
                schema: new OA\Schema(ref: new Model(type: QueryParams::class))
            ),
        ],
    )]
    #[OA\Response(
        response: 200,
        description: 'Users collection',
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
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                ref: new Model(type: CommonOutputContract::class),
                                type: 'object',
                            )
                        ),
                        new OA\Property(
                            property: 'pagination',
                            ref: new Model(type: OutputPagination::class),
                            type: 'object'
                        ),
                    ],
                    type: 'object'
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
        path: '/api/admin/auth/users.{_format}',
        name: self::NAME,
        defaults: ['_format' => 'json'],
        methods: ['GET'],
    )]
    public function action(
        SearchQuery $searchQuery,
        Bus $bus,
        OutputFormat $outputFormat,
        Presenter $presenter,
    ): Response {
        $query = new Query(
            targetEntityClass: User::class,
            pagination: $searchQuery->pagination,
            filters: $searchQuery->filters,
            sorts: $searchQuery->sorts
        );

        $searchResult = $bus->query($query);

        return $presenter->present(
            data: ApiFormatter::prepare([
                'data' => array_map(static function (User $user) {
                    return CommonOutputContract::create($user);
                }, $searchResult->entities),
                'pagination' => $searchResult->pagination,
            ]),
            outputFormat: $outputFormat
        );
    }
}
