<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'app.admin.ui.modules.auth.user.other_labels.old_password',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new UserPassword(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'app.admin.ui.modules.auth.user.properties.password',
                'required' => true,
                'first_options' => [
                    'label' => 'app.admin.ui.modules.auth.user.properties.password',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'password_is_short',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'app.admin.ui.modules.auth.user.actions.password_repeat',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'password_is_short',
                            'max' => 4096,
                        ]),
                    ],
                ],
            ]);
    }
}
