<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Controller\User;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'auth/users';
    protected $baseRoutePattern = 'auth/users';

    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class);
        $form->add('email', EmailType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('id');
        $filter->add('createdAt');
        $filter->add('updatedAt');
        $filter->add('name');
        $filter->add('email');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('createdAt');
        $list->addIdentifier('updatedAt');
        $list->addIdentifier('name');
        $list->addIdentifier('email');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('createdAt');
        $show->add('updatedAt');
        $show->add('name');
        $show->add('email');
    }
}