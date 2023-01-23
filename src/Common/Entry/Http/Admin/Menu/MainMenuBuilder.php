<?php

declare(strict_types=1);

namespace App\Common\Entry\Http\Admin\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MainMenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly SectionBuilderFactory $sectionBuilderFactory
    ) {
    }

    public function buildMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        foreach ($this->sectionBuilderFactory->getSortedBuilders() as $sectionBuilder) {
            $sectionBuilder->build($menu);
        }

        return $menu;
    }
}
