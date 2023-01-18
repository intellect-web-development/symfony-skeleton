<?php

declare(strict_types=1);

namespace App\Common\Entry\Http;

use App\Common\Service\Metrics\AdapterInterface;
use App\Common\Service\Metrics\MetricsGuard;
use Prometheus\RenderTextFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricsAction extends AbstractController
{
    #[Route(path: '/metrics', name: 'metrics', methods: ['GET'])]
    public function metrics(
        Request $request,
        AdapterInterface $adapter,
        MetricsGuard $metricsGuard
    ): Response {
        $metricsGuard->guard(
            $request->getHost()
        );

        return new Response(
            (new RenderTextFormat())->render($adapter->collect()),
            200,
            ['Content-Type' => 'text/plain']
        );
    }
}
