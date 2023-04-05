<?php

declare(strict_types=1);

namespace App\Common\Service\Suggester;

interface SuggesterInterface
{
    public function getSuggesterName(): string;
}
