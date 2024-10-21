<?php

declare(strict_types=1);

namespace App\Common\Entry\Http;

use App\Common\Dictionary\EnvDictionary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ApiInfoAction extends AbstractController
{
    public const NAME = 'api_info';

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[Cache(maxage: 3600, public: true, mustRevalidate: true)]
    #[Route(path: '/api/info', name: self::NAME, methods: ['GET'], env: EnvDictionary::DEV)]
    #[Route(path: '/api/info', name: self::NAME, methods: ['GET'], env: EnvDictionary::TEST)]
    public function actionDev(): JsonResponse
    {
        return new JsonResponse([
            'api' => [
                'doc' => [
                    'ui' => $this->router->generate('app.swagger', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'json' => $this->router->generate('app.swagger_ui', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
            ],
        ]);
    }
}
