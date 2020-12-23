<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $status = $builder->getData()->getStatus();
        $builder
            ->add('name', null, [
                'attr'=>[
                    'disabled' => true
                ]
            ])
            ->add('active')
            ;
        if(in_array($status, Order::STATUS_CHANGE)){
            $builder->add('status', ChoiceType::class,
                [
                    'choices' =>   Order::STATUS_CHANGE,
                    'attr' => ['class' => 'select2 custom-select select2 custom-select-lg mb-3']

                ])
            ;
        }else{
            $builder->add('status',null,
                [
                    'attr' => ['disabled' => true]
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
