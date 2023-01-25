<?php

namespace App\Common\Entry\Http\Admin\Form\Filter;

use Sirian\SuggestBundle\Form\Type\SuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Select2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add($options['field_name'], SuggestType::class, [
            'suggester' => $options['suggester'],
            'label' => $options['label'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['field_name', 'label', 'suggester'])
            ->setAllowedTypes('field_name', ['string'])
            ->setAllowedTypes('suggester', ['string'])
            ->setAllowedTypes('label', ['string']);
    }
}
