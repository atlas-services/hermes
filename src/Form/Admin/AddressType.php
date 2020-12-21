<?php

namespace App\Form\Admin;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('locale')
            ->add('additionalName')
            ->add('addressLine1')
            ->add('addressLine2')
            ->add('administrativeArea')
            ->add('countryCode')
            ->add('dependentLocality')
            ->add('familyName')
            ->add('givenName')
            ->add('locality')
            ->add('organization')
            ->add('postalCode')
            ->add('sortingCode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
