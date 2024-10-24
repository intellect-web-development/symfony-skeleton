<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Token\InvalidateAllRefreshTokens;

use App\Auth\Infrastructure\Security\RefreshTokenCache;
use App\Auth\Infrastructure\Security\UserIdentity;
use IWD\SymfonyEntryContract\Dto\Input\OutputFormat;
use IWD\SymfonyEntryContract\Dto\Output\ApiFormatter;
use IWD\SymfonyEntryContract\Service\Presenter;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Action extends AbstractController
{
    #[OA\Tag(name: 'Auth.Token')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            description: 'Invalidate all JWT-Tokens',
            required: true,
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Tokens success invalidated',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'status',
                    type: 'integer',
                    example: 200,
                ),
                new OA\Property(
                    property: 'ok',
                    type: 'boolean',
                    example: true
                ),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                    )
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
    #[Route(
        path: '/api/token/invalidate-all-refresh-tokens',
        name: 'token.invalidateAllRefreshTokens',
        methods: ['POST']
    )]
    public function invalidateRefreshToken(
        Presenter $presenter,
        RefreshTokenCache $refreshTokenCache,
        UserIdentity $user
    ): Response {
        $refreshTokenCache->invalidateAll($user->id);

        return $presenter->present(
            data: ApiFormatter::prepare(
                messages: ['Invalidated done']
            ),
            outputFormat: new OutputFormat('json')
        );
    }
}
