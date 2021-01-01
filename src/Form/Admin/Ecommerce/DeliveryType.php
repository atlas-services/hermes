<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use App\Entity\Delivery;
use App\Repository\AddressRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, [
                'label' => 'order.delivery',
            ])
            ->add('deliveryMethod', ChoiceType::class,
                [
                    'label' =>   'order.delivery_method',
                    'choices' =>   Delivery::DELIVERY_CHOICES,
                    'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']

                ])
            ->add('address', EntityType::class,
                [
                    'class' => 'App\Entity\Address',
                    'label' =>   'order.delivery_address',
                    'attr' => [
                        'class' => 'select2 custom-select select2 custom-select-lg mb-3',
//                    'query_builder' => function (AddressRepository $er) {
//                            return $er->createQueryBuilder('a')
//                                ->where('a.active = 1')
//                                ;
//                        }
                        ]
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }
}
