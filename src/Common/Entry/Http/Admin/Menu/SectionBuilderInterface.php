<?php

declare(strict_types=1);

namespace App\Common\Entry\Http\Admin\Menu;

use Knp\Menu\ItemInterface;

interface SectionBuilderInterface
{
    public function build(ItemInterface $menu): void;

    public function getOrder(): int;
}
