<?php

declare(strict_types=1);

namespace App\Common\Entry\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RootController extends AbstractController
{
    #[Route(path: '', name: 'root', methods: ['GET'])]
    public function root(): JsonResponse
    {
        return new JsonResponse([
            'api' => [
                'version' => '1.0',
            ],
        ]);
    }
}
