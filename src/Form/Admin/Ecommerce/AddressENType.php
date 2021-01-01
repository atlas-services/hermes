<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressENType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('defaultDelivery', CheckboxType::class,
            array('label' => "Adrresse livraison par défault"))
            ->add('defaultInvoice',CheckboxType::class,
            array('label' => "Adrresse facturation par défault"))
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
