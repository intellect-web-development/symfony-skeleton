<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Controller\User;

use App\Auth\Application\User\UseCase\Create\Command as UserCreateCommand;
use App\Auth\Application\User\UseCase\Create\Handler as UserCreateHandler;
use App\Auth\Domain\User\User;
use App\Auth\Entry\Http\Admin\Controller\User\Form\CreateType;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserAdminController extends CRUDController
{
    public function __construct(
        private readonly UserCreateHandler $userCreateHandler,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function listAction(Request $request): Response
    {
        return parent::listAction($request);
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
                    role: User::ROLE_ADMIN,
                    name: $payload['name'],
                )
            );
            if ($result->isEmailIsBusy()) {
                $this->addFlash('error', $this->translator->trans('app.admin.ui.modules.auth.user.flash.error_email_is_busy'));
            }
            if ($result->isSuccess()) {
                $this->addFlash('success', $this->translator->trans('app.admin.ui.modules.auth.user.flash.success_created'));

                return $this->redirectToRoute('auth/users_show', ['id' => $result->user?->getId()->getValue()]);
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

    public function showAction(Request $request): Response
    {
        return parent::showAction($request);
    }

    public function editAction(Request $request): Response
    {
        dd($request);

        return parent::editAction($request); // TODO: Change the autogenerated stub
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
