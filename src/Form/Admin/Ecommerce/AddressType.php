<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Hermes\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('defaultDelivery')
            ->add('defaultInvoice')
            ->add('additionalName')
            ->add('addressLine1')
            ->add('addressLine2')
            ->add('countryCode')
            ->add('familyName')
            ->add('givenName')
            ->add('locality')
            ->add('organization')
            ->add('postalCode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
