<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Controller\User;

use App\Auth\Application\User\UseCase\ChangePassword\Command as ChangePasswordCommand;
use App\Auth\Application\User\UseCase\ChangePassword\Handler as ChangePasswordHandler;
use App\Auth\Application\User\UseCase\Create\Command as CreateCommand;
use App\Auth\Application\User\UseCase\Create\Handler as CreateHandler;
use App\Auth\Application\User\UseCase\Delete\Command as DeleteCommand;
use App\Auth\Application\User\UseCase\Delete\Handler as DeleteHandler;
use App\Auth\Application\User\UseCase\Edit\Command as EditCommand;
use App\Auth\Application\User\UseCase\Edit\Handler as EditHandler;
use App\Auth\Domain\User\User;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Entry\Http\Admin\Controller\User\Form\ChangePasswordType;
use App\Auth\Entry\Http\Admin\Controller\User\Form\CreateType;
use App\Auth\Entry\Http\Admin\Controller\User\Form\EditType;
use App\Auth\Infrastructure\Security\JwtTokenizer;
use App\Auth\Infrastructure\Security\UserIdentity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

//#[Route(path: '/users', name: 'app_user_')]
//#[AutoconfigureTag()]
class UserController extends CRUDController
{
    public function listAction(Request $request): Response
    {
        return $this->renderWithExtraParams('Admin\AuditLog\audit_log.list.twig');
    }
//    public function createAction()
//    {
//        // ...
//    }
//
//    public function editAction($id)
//    {
//        // ...
//    }
//
//    public function deleteAction($id)
//    {
//        // ...
//    }
//    #[Route(path: '/create/new', name: 'create', methods: ['GET', 'POST'])]
//    public function create(
//        Request $request,
//        CreateHandler $handler,
//        TranslatorInterface $translator,
//    ): Response {
//        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
//        $form = $this->createForm(CreateType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $payload = $form->getData();
//            $result = $handler->handle(
//                new CreateCommand(
//                    email: $payload['email'],
//                    plainPassword: $payload['plainPassword'],
//                    role: $payload['role'],
//                    name: $payload['name'],
//                )
//            );
//            if ($result->isEmailIsBusy()) {
//                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
//            }
//            if ($result->isSuccess()) {
//                $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_created'));
//
//                return $this->redirectToRoute('app_user_show', ['id' => $result->user?->getId()->getValue()]);
//            }
//        }
//
//        return $this->render(
//            '@app/admin/layout/crud/create.html.twig',
//            [
//                'metadata' => $this->metadata,
//                'form' => $form->createView(),
//                'resource' => $form->getData(),
//                'configuration' => $configuration,
//            ]
//        );
//    }
//
//    #[Route(
//        path: '/{id}/edit',
//        name: 'update',
//        methods: ['GET', 'POST'],
//    )]
//    public function update(
//        Request $request,
//        EditHandler $handler,
//        TranslatorInterface $translator,
//    ): Response {
//        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
//
//        /** @var User $user */
//        $user = $this->findOr404($configuration);
//        if (Request::METHOD_GET === $request->getMethod()) {
//            $form = $this->createForm(EditType::class, $user);
//        } else {
//            $form = $this->createForm(EditType::class);
//        }
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $payload = $form->getData();
//            $result = $handler->handle(
//                new EditCommand(
//                    id: $user->getId(),
//                    name: $payload['name'],
//                    email: $payload['email'],
//                    role: $payload['role'],
//                )
//            );
//            if ($result->isEmailIsBusy()) {
//                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
//            }
//            if ($result->isUserNotExists()) {
//                $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
//            }
//            if ($result->isSuccess()) {
//                $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_edited'));
//
//                return $this->redirectToRoute('app_user_show', ['id' => $result->user?->getId()->getValue()]);
//            }
//        }
//
//        return $this->render(
//            '@auth/admin/user/update.html.twig',
//            [
//                'metadata' => $this->metadata,
//                'form' => $form->createView(),
//                'resource' => $form->getData(),
//                'configuration' => $configuration,
//            ]
//        );
//    }
//
//    #[Route(
//        path: '/{id}/delete',
//        name: 'delete',
//        methods: ['POST'],
//    )]
//    public function delete(
//        string $id,
//        TranslatorInterface $translator,
//        DeleteHandler $handler
//    ): Response {
//        $result = $handler->handle(
//            new DeleteCommand(
//                id: new UserId($id)
//            )
//        );
//        if ($result->isUserNotExists()) {
//            $this->addFlash('error', $translator->trans('app.admin.ui.modules.auth.user.flash.error_user_not_exists'));
//        }
//        if ($result->isSuccess()) {
//            $this->addFlash('success', $translator->trans('app.admin.ui.modules.auth.user.flash.success_deleted'));
//        }
//
//        return $this->redirectToRoute('app_user_index');
//    }
}
