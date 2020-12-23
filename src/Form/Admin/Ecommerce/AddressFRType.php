<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressFRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('active')
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
                    'attr' => array('class' => "form-control")))

           /* ->add('countryCode', ChoiceType::class,
                array('label' => "* Pays", ChoiceType::class, 
                'attr' => array(
                    'placeholder'       => 'Pays',
                )))*/
    
            ->add('locality', TextType::class,
            array('label' => "* Ville",
                'attr' => array('class' => "form-control", 'required' => true)))

            ->add('postalCode', TextType::class,
            array('label' => "* Code postal",
                'attr' => array('class' => "form-control", 'required' => true)))

         
                    
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
        ]);
    }
}
