<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Common\Entry\Http\HealthCheckAction;
use Symfony\Component\Routing\RouterInterface;

class HealthCheckTest extends FunctionalTestCase
{
    private static RouterInterface $router;

    protected function setUp(): void
    {
        parent::setUp();
        self::$router = self::$containerTool->get(RouterInterface::class);
    }

    public function testSuccess(): void
    {
        $url = self::$router->generate(HealthCheckAction::NAME);
        self::assertEquals(200, $this->request('GET', $url)->getStatusCode());
    }
}
