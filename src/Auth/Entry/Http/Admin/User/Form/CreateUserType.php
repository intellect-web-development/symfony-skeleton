<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.admin.ui.modules.auth.user.properties.name',
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
                'empty_data' => '',
            ])
            ->add('email', EmailType::class, [
                'label' => 'app.admin.ui.modules.auth.user.properties.email',
                'constraints' => [
                    new Email(),
                    new NotBlank(allowNull: false),
                ],
                'empty_data' => '',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'app.admin.ui.modules.auth.user.properties.password',
                'invalid_message' => 'password_is_not_equals',
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
            ])
        ;
    }
}
