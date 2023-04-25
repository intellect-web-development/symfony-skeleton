<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\User;

use App\Auth\Core\User\Application\UseCase\ChangePassword\Command as ChangePasswordCommand;
use App\Auth\Core\User\Application\UseCase\ChangePassword\Handler as ChangePasswordHandler;
use App\Auth\Core\User\Application\UseCase\Create\Command as CreateCommand;
use App\Auth\Core\User\Application\UseCase\Create\Handler as CreateHandler;
use App\Auth\Core\User\Application\UseCase\Delete\Command as DeleteCommand;
use App\Auth\Core\User\Application\UseCase\Delete\Handler as DeleteHandler;
use App\Auth\Core\User\Application\UseCase\Edit\Command as EditCommand;
use App\Auth\Core\User\Application\UseCase\Edit\Handler as EditHandler;
use App\Auth\Core\User\Domain\User;
use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Auth\Entry\Http\Admin\User\Form\ChangePasswordType;
use App\Auth\Entry\Http\Admin\User\Form\CreateUserType;
use App\Auth\Entry\Http\Admin\User\Form\MainInfoType;
use App\Auth\Entry\Http\Admin\User\Form\UserType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/users', name: 'app_user_')]
class UserController extends ResourceController
{
    #[Route(path: '/create/new', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        CreateHandler $handler,
        TranslatorInterface $translator,
    ): Response {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $form = $this->createForm(CreateUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload = $form->getData();
            $result = $handler->handle(
                new CreateCommand(
                    plainPassword: $payload['plainPassword'],
                    name: $payload['name'],
                    email: $payload['email'],
                )
            );
            if ($result->isEmailIsBusy()) {
                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_created'));

                return $this->redirectToRoute('app_user_show', ['id' => $result->user?->getId()->getValue()]);
            }
        }

        return $this->render(
            'admin/layout/crud/create.html.twig',
            [
                'metadata' => $this->metadata,
                'form' => $form->createView(),
                'resource' => $form->getData(),
                'configuration' => $configuration,
            ]
        );
    }

    #[Route(
        path: '/{id}/edit',
        name: 'update',
        methods: ['GET', 'POST'],
    )]
    public function update(
        Request $request,
        EditHandler $handler,
        TranslatorInterface $translator,
    ): Response {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var User $user */
        $user = $this->findOr404($configuration);
        if (Request::METHOD_GET === $request->getMethod()) {
            $form = $this->createForm(UserType::class, $user);
        } else {
            $form = $this->createForm(UserType::class);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload = $form->getData();
            $result = $handler->handle(
                new EditCommand(
                    id: $user->getId(),
                    name: $payload['name'],
                    email: $payload['email'],
                )
            );
            if ($result->isEmailIsBusy()) {
                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
            }
            if ($result->isUserNotExists()) {
                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_edited'));

                return $this->redirectToRoute('app_user_show', ['id' => $result->user?->getId()->getValue()]);
            }

            return $this->redirectToRoute('app_user_edit');
        }

        return $this->render(
            'admin/auth/user/update.html.twig',
            [
                'metadata' => $this->metadata,
                'form' => $form->createView(),
                'resource' => $form->getData(),
                'configuration' => $configuration,
            ]
        );
    }

    #[Route(
        path: '/{id}/delete',
        name: 'delete',
        methods: ['POST'],
    )]
    public function delete(
        string $id,
        TranslatorInterface $translator,
        DeleteHandler $handler
    ): Response {
        $result = $handler->handle(
            new DeleteCommand(
                id: new Id($id)
            )
        );
        if ($result->isUserNotExists()) {
            $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
        }
        if ($result->isSuccess()) {
            $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_deleted'));
        }

        return $this->redirectToRoute('app_user_index');
    }

    #[Route(path: '/account/show', name: 'account', methods: ['GET', 'POST'])]
    public function account(
        Request $request,
        TranslatorInterface $translator,
        ChangePasswordHandler $changePasswordHandler,
        EditHandler $editHandler
    ): Response {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var User $user */
        $user = $this->getUser();

        if (Request::METHOD_GET === $request->getMethod()) {
            $mainInfoForm = $this->createForm(MainInfoType::class, $user);
            $changePasswordForm = $this->createForm(ChangePasswordType::class, $user);
        } else {
            $mainInfoForm = $this->createForm(MainInfoType::class);
            $changePasswordForm = $this->createForm(ChangePasswordType::class);
        }

        $mainInfoForm->handleRequest($request);
        $changePasswordForm->handleRequest($request);

        if ($mainInfoForm->isSubmitted() && $mainInfoForm->isValid()) {
            $payload = $mainInfoForm->getData();
            $result = $editHandler->handle(
                new EditCommand(
                    id: $user->getId(),
                    name: $payload['name'],
                    email: $payload['email'],
                )
            );
            if ($result->isEmailIsBusy()) {
                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
            }
            if ($result->isUserNotExists()) {
                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_edited'));
            }

            return $this->redirectToRoute('app_user_account');
        }

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $payload = $changePasswordForm->getData();

            /** @var array|null $changePassword */
            $changePassword = $request->request->all()['change_password'] ?? null;
            if (!is_array($changePassword)) {
                $changePassword = [];
            }
            $oldPassword = array_key_exists('oldPassword', $changePassword)
                ? $changePassword['oldPassword']
                : ''
            ;

            $result = $changePasswordHandler->handle(
                new ChangePasswordCommand(
                    id: $user->getId(),
                    newPassword: $payload['plainPassword'],
                    oldPassword: $oldPassword
                )
            );
            if ($result->isInvalidCredentials()) {
                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_invalid_credentials'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_password_was_updated'));
            }

            return $this->redirectToRoute('app_user_account');
        }

        return $this->render('admin/auth/user/account.html.twig', [
            'configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $user,
            $this->metadata->getName() => $user,
            'mainInfoForm' => $mainInfoForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView(),
        ]);
    }
}
