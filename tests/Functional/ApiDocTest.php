<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class ApiDocTest extends FunctionalTestCase
{
    public function testSuccess(): void
    {
        self::assertEquals(200, $this->request('GET', '/api/doc')->getStatusCode());
        self::assertEquals(200, $this->request('GET', '/api/doc.json')->getStatusCode());
    }
}
