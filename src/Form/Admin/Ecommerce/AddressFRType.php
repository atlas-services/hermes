<?php

namespace App\Form\Admin\Ecommerce;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Console\Descriptor\TextDescriptor;
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
<<<<<<< HEAD
        array('label' => 'form_adress.defaultDelivery'))

        ->add('defaultInvoice',CheckboxType::class,
        array('label' => 'form_adress.defaultInvoice'))
=======
        array('label' => "Addresse livraison par défault", 'required' => false))

        ->add('defaultInvoice',CheckboxType::class,
        array('label' => "Adrresse facturation par défault",'required' => false))
>>>>>>> dev-stripe

        ->add('additionalName', TextType::class,
                 array('label' => "* Nom de l'adresse",
                       'attr' => array('class' => "form-control",
                                    'data-validation-required-message' => 'form_adress.additionalName_placeholder',
                                    'required' => true)))

        ->add('addressLine1', TextType::class,
            array('label' => "* Addresse",
                'attr' => array('class' => "form-control",
                                'data-validation-required-message' => 'form_adress.addressLine1',
                                'required' => true)))
                                

        ->add('addressLine2', TextType::class,
            array('label' => "Addresse suite",
                'attr' => array('class' => "form-control",'required' => false)))

        ->add('countryCode',ChoiceType::class,
            array('label' => "* Pays",
            'attr' => array('class' => "form-control"),
            'choices' => array(current($optionAddress) => key($optionAddress))))

            
        ->add('postalCode', TextType::class,
            array('label' => "* Code postal",'attr' => array('class' => "form-control",'autocomplete' => "off")))

        ->add('locality', TextType::class,
            array('label' => "* Ville",'attr' => array('class' => "form-control",'autocomplete' => "off",)))

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
