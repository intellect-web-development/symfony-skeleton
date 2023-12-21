<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Controller\User;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class AuthUsersAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'auth/users';
    protected $baseRoutePattern = 'auth/users';

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->clearExcept(['list']);
        $collection->add('auth/users', 'auth/users');
    }
}
