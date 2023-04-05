<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Suggester;

use App\Auth\Core\User\Domain\User;
use App\Common\Service\Suggester\SuggesterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class UserSuggester extends AbstractType implements SuggesterInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => User::class,
            'choice_label' => 'name',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }

    public function getSuggesterName(): string
    {
        return 'auth_user';
    }
}
