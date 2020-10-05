<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public $emailTo;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->emailTo = $options['emailTo'];
        $builder
            ->add('name', TextType::class,
                array('constraints' => new Assert\Type('string'),
                    'label' => 'Nom', 'attr' => array('class' => "form-control", 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre nom.",
                    'placeholder' => "formulaire.name_placeholder")))
            ->add('email', EmailType::class,
                array('label' => '* Email ', 'attr' => array('class' => "form-control", 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre émail.",
                    'placeholder' => "formulaire.email_placeholder")))
            ->add('telephone', TextType::class,
                array('constraints' => new Assert\Length(['min' => 10, 'max' => 10, 'exactMessage' => 'contact.message.telephone']),
                    'label' => 'Téléphone ', 'required' => false, 'attr' => array('class' => "form-control", 'id' => "phone",
                    'data-validation-required-message' => "error_phone.", 'placeholder' => "formulaire.phone_placehoder")))
            ->add('message', TextareaType::class,
                array('label' => '* Votre message ', 'attr' => array('class' => "form-control", 'id' => "message", 'data-validation-required-message' => "Please enter your name.",
                    'placeholder' => "formulaire.message_placeholder", 'rows' => '15')));
    }

    public function getName()
    {
        return 'contact';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'emailTo' => null,
        ]);
    }
}
