<?php

namespace App\Common\Entry\Http\Admin\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UuidType as CoreUuidType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UuidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add($options['field_name'], CoreUuidType::class, [
            'label' => $options['label'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(['field_name' => 'uuid', 'label' => false, 'empty_data' => null])
            ->setAllowedTypes('field_name', ['string'])
            ->setAllowedTypes('label', ['string', 'bool']);
    }
}
