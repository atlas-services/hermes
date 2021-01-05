<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList as ChoiceListChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddressFRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionAddress = $options['optionAddress'];
        
                          
        $builder
        ->add('active')
        ->add('defaultDelivery', CheckboxType::class,
        array('label' => "Adrresse livraison par défault"))

        ->add('defaultInvoice',CheckboxType::class,
        array('label' => "Adrresse facturation par défault"))

        ->add('additionalName', TextType::class,
                 array('label' => "* Nom de l'adresse",
                       'attr' => array('class' => "form-control",
                                    'data-validation-required-message' => "Merci de saisir le nom de l'adresse",
                                    'required' => true)))

          
            ->add('addressLine1', TextType::class,
                array('label' => "* Addresse",
                    'attr' => array('class' => "form-control",
                                    'data-validation-required-message' => "Merci de saisir l'adresse 1",
                                    'required' => true)))
                                    

            ->add('addressLine2', TextType::class,
                array('label' => "Addresse suite",
                    'attr' => array('class' => "form-control",'required' => false)))

            ->add('countryCode',ChoiceType::class,
                array('label' => "* Pays",
                'attr' => array('class' => "form-control"),
                'choices' => array(current($optionAddress) => key($optionAddress))))

              
            ->add('locality', TextType::class,
                array('label' => "* Ville",'attr' => array('class' => "form-control")))


            ->add('postalCode', ChoiceType::class,
                array('label' => "* Code postal",
                    'choices' => array( 
                        '75008' => 0,
                        '78370' => 1,
                        '92000' => 2
                    ),
                    'placeholder' => 'Veuillez sélectionner le code postal'
                ))
         
                    
            ->add('familyName', TextType::class,
            array('label' => "* Nom",
                'attr' => array('class' => "form-control", 'required' => true)))

            ->add('givenName', TextType::class,
            array('label' => "* Prénom",
                'attr' => array('class' => "form-control", 'required' => true)))

            ->add('organization', TextType::class,
                array('label' => "Organisation",
                    'attr' => array('class' => "form-control")))
        ;

       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'optionAddress' => 0
        ]);
    }
}
