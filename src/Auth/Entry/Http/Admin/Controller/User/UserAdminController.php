<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Controller\User;

use App\Auth\Application\User\UseCase\Create\Command as UserCreateCommand;
use App\Auth\Application\User\UseCase\Create\Handler as UserCreateHandler;
use App\Auth\Application\User\UseCase\Edit\Command as UserEditCommand;
use App\Auth\Application\User\UseCase\Edit\Handler as UserEditHandler;
use App\Auth\Application\User\UseCase\Delete\Command as UserDeleteCommand;
use App\Auth\Application\User\UseCase\Delete\Handler as UserDeleteHandler;
use App\Auth\Domain\User\User;
use App\Auth\Entry\Http\Admin\Controller\User\Form\CreateType;
use App\Auth\Entry\Http\Admin\Controller\User\Form\EditType;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserAdminController extends CRUDController
{
    public function __construct(
        private readonly UserCreateHandler $userCreateHandler,
        private readonly UserEditHandler $userEditHandler,
        private readonly UserDeleteHandler $userDeleteHandler,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function createAction(Request $request): Response
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload = $form->getData();
            $result = $this->userCreateHandler->handle(
                new UserCreateCommand(
                    email: $payload['email'],
                    plainPassword: $payload['plainPassword'],
                    role: $payload['role'],
                    name: $payload['name'],
                )
            );
            if ($result->isEmailIsBusy()) {
                $this->addFlash('error', $this->translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $this->translator->trans('app.admin.ui.modules.auth.user.flash.success_created'));

                return $this->redirectToRoute('app_auth_users_show', ['id' => $result->user?->getId()->getValue()]);
            }
        }

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('@auth/admin/user/create.html.twig', [
            'action' => 'create',
            'form' => $formView,
            'object' => $form->getData(),
            'objectId' => null,
        ]);
    }

    public function editAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->assertObjectExists($request, true);

        if (Request::METHOD_GET === $request->getMethod()) {
            $form = $this->createForm(EditType::class, $user);
        } else {
            $form = $this->createForm(EditType::class);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload = $form->getData();
            $result = $this->userEditHandler->handle(
                new UserEditCommand(
                    id: $user->getId(),
                    name: $payload['name'],
                    email: $payload['email'],
                    role: $payload['role'],
                )
            );
            if ($result->isEmailIsBusy()) {
                $this->addFlash('error', $this->translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
            }
            if ($result->isUserNotExists()) {
                $this->addFlash('error', $this->translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $this->translator->trans('app.admin.ui.modules.auth.user.flash.success_edited'));

                return $this->redirectToRoute('app_auth_users_show', ['id' => $result->user?->getId()->getValue()]);
            }
        }

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('@auth/admin/user/update.html.twig', [
            'action' => 'edit',
            'form' => $formView,
            'object' => $form->getData(),
            'objectId' => $user->getId()->getValue(),
        ]);
    }

    public function deleteAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->assertObjectExists($request, true);

        $result = $this->userDeleteHandler->handle(
            new UserDeleteCommand(
                id: $user->getId(),
            )
        );
        if ($result->isUserNotExists()) {
            $this->addFlash('error', $this->translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
        }
        if ($result->isSuccess()) {
            $this->addFlash('success', $this->translator->trans('app.admin.ui.modules.auth.user.flash.success_deleted'));
        }

        return $this->redirectToRoute('app_auth_users_list');
    }

    public function batchActionDelete(ProxyQueryInterface $query): Response
    {
        /** @var User $user */
        foreach ($query->getDoctrineQuery()->toIterable() as $user) {
            $result = $this->userDeleteHandler->handle(
                new UserDeleteCommand(
                    id: $user->getId(),
                )
            );
            if ($result->isUserNotExists()) {
                $this->addFlash('error', $this->translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $this->translator->trans('app.admin.ui.modules.auth.user.flash.success_deleted'));
            }
        }

        return $this->redirectToRoute('app_auth_users_list');
    }

    public function showAction(Request $request): Response
    {
        return parent::showAction($request);
    }

    public function listAction(Request $request): Response
    {
        return parent::listAction($request);
    }
}
