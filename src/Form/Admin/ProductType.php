<?php

namespace App\Form\Admin;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('code')
            ->add('name')
            ->add('summary')
            ->add('post', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'required' => false,
                'class' => 'App\Entity\Post',
                'choice_label' => 'name',
//                    'label_format' => 'section.template',
                'attr'=> ['class' => 'select2 custom-select select2 custom-select-lg mb-3']
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
