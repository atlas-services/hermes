<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionAddress = $options['optionAddress'];
        $country = array_flip($optionAddress['countryList']);
        
        $builder
                ->add(
                    'countryCode',
                    ChoiceType::class,
                    array('label' => "* Pays",
                        'choices' => $country,
                        'placeholder' => 'Veuillez sélectionner le pays'
         )
                );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'optionAddress' => 0
        ]);
    }
}
