<?php

namespace App\Form\Admin;

use App\Form\Admin\AbstractBaseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TemoignageType extends AbstractBaseType
{
    public $emailTo;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->emailTo = $options['emailTo'];
        $builder
            ->add('active')
            ->add('position')
            ->add('name', TextType::class,
                array('constraints' => new Assert\Type('string'),
                    'label' => 'Nom', 'attr' => array('class' => "form-control", 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre nom.",
                    'placeholder' => "formulaire.name_placeholder")))
            ->add('email', EmailType::class,
                array('label' => '* Email ', 'attr' => array('class' => "form-control", 'id' => "name", 'data-validation-required-message' => "Merci de saisir votre émail.",
                    'placeholder' => "formulaire.email_placeholder")))
             ->add('content', TextareaType::class,
                array('label' => '* Votre témoignage ', 'attr' => array('class' => "form-control", 'id' => "message", 'data-validation-required-message' => "Merci de saisir votre message.",
                    'placeholder' => "formulaire.message_placeholder", 'rows' => '15')))
            ->add('save', SubmitType::class, [
                'icon_before' => '<i class="fa fa-save"></i>',
                'label_html' => true,
                'label' => 'global.update'
            ]);;
    }

    public function getName()
    {
        return 'temoignage';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'emailTo' => null,
        ]);
    }
}
