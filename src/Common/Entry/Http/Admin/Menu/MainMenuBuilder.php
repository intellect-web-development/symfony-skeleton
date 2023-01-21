<?php

declare(strict_types=1);

namespace App\Common\Entry\Http\Admin\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MainMenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory,
    ) {
    }

    public function buildMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $this->buildAuthBlock($menu);

        return $menu;
    }

    private function buildAuthBlock(ItemInterface $menu): void
    {
        $settings = $menu
            ->addChild('auth')
            ->setLabel('app.admin.ui.menu.main.auth.label');

        $settings
            ->addChild(
                'user',
                [
                    'route' => 'app_user_index',
                ]
            )
            ->setLabel('app.admin.ui.menu.main.auth.user.list')
            ->setLabelAttribute('icon', 'users');
    }
}
