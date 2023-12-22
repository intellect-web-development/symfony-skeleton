<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Controller\User;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UserAdminController extends CRUDController
{
    public function listAction(Request $request): Response
    {
        return parent::listAction($request);
    }

    public function showAction(Request $request): Response
    {
        dd($request);
        return parent::showAction($request); // TODO: Change the autogenerated stub
    }

    public function deleteAction(Request $request): Response
    {
        return parent::deleteAction($request); // TODO: Change the autogenerated stub
        dd($request);
    }

    public function batchActionDelete(ProxyQueryInterface $query): Response
    {
        dd($query);
        return parent::batchActionDelete($query); // TODO: Change the autogenerated stub
    }
}
